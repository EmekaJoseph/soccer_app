<?php

namespace App\Http\Controllers;

use App\Models\FeedbackModel;
use App\Models\MatchModel;
use App\Models\PlayerModel;
use App\Models\PredictionModel;
use App\Models\ResultModel;
use App\Models\TeamModel;
use App\Models\TournamentModel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;

/**
 * Read-only data for the public stats page (the link owners share with fans),
 * plus the two things fans can submit: predictions and feedback.
 */
class PublicViewController extends Controller
{
    public function tournament(TournamentModel $tournament): JsonResponse
    {
        return response()->json($tournament);
    }

    public function standings(TournamentModel $tournament): JsonResponse
    {
        $teams = $tournament->relatedTeams()->get()->keyBy('team_id');

        $rows = $tournament->standings()->get()
            ->filter(fn ($row) => $teams->has($row->team_id))
            ->map(fn ($row) => [
                ...$row->toArray(),
                'team_name' => $teams[$row->team_id]->team_name,
                'team_color' => $teams[$row->team_id]->team_color,
                'team_badge' => $teams[$row->team_id]->team_badge,
            ]);

        $sort = fn (Collection $rows) => $rows->sortBy([
            ['points', 'desc'],
            ['goal_diff', 'desc'],
            ['won', 'desc'],
            ['team_name', 'asc'],
        ])->values();

        if (! $tournament->isCup()) {
            return response()->json($sort($rows));
        }

        $groups = $rows->groupBy('group_in')
            ->map(fn (Collection $teams, $group) => ['group' => $group, 'teams' => $sort($teams)])
            ->sortBy('group')
            ->values();

        return response()->json($groups);
    }

    public function results(TournamentModel $tournament): JsonResponse
    {
        $names = $tournament->relatedTeams()->get()->keyBy('team_id');

        $results = $tournament->results()
            ->orderByDesc('date_played')
            ->get()
            ->map(fn (ResultModel $result) => [
                ...$result->only([
                    'result_id', 'match_id', 'home_team', 'away_team', 'home_score', 'away_score',
                    'home_score_pen', 'away_score_pen', 'match_stage', 'date_played',
                ]),
                'winner' => $result->winnerId(),
                'home_name' => $names[$result->home_team]->team_name ?? null,
                'away_name' => $names[$result->away_team]->team_name ?? null,
                'home_color' => $names[$result->home_team]->team_color ?? null,
                'away_color' => $names[$result->away_team]->team_color ?? null,
                'home_badge' => $names[$result->home_team]->team_badge ?? null,
                'away_badge' => $names[$result->away_team]->team_badge ?? null,
            ]);

        return response()->json($results);
    }

    /** Upcoming fixtures (matches without a result yet), soonest first. */
    public function matches(TournamentModel $tournament): JsonResponse
    {
        $matches = $tournament->matches()
            ->whereDoesntHave('result')
            ->with(['homeTeam', 'awayTeam'])
            ->orderBy('kick_off')
            ->get()
            ->filter(fn (MatchModel $m) => $m->homeTeam && $m->awayTeam)
            ->values();

        return response()->json($matches);
    }

    public function live(TournamentModel $tournament): JsonResponse
    {
        return response()->json(
            $tournament->liveMatches()->with(['homeTeam', 'awayTeam'])->get()
                ->map(fn ($live) => [
                    ...$live->only(['live_id', 'match_id', 'match_stage', 'home_team_score', 'away_team_score', 'curr_time', 'isPaused']),
                    'home_team' => $live->homeTeam,
                    'away_team' => $live->awayTeam,
                ])
        );
    }

    /**
     * Every team's record across all results (league, group and knock-out),
     * with a performance rating: share of available points won, 0-100.
     */
    public function teams(TournamentModel $tournament): JsonResponse
    {
        $results = $tournament->results()->get();

        $teams = $tournament->relatedTeams()->withCount('players')->get()->map(function (TeamModel $team) use ($results) {
            $stats = ['played' => 0, 'won' => 0, 'draw' => 0, 'lost' => 0, 'scored' => 0, 'conceded' => 0];

            foreach ($results as $result) {
                $isHome = $result->home_team === $team->team_id;
                if (! $isHome && $result->away_team !== $team->team_id) {
                    continue;
                }

                [$for, $against] = $isHome
                    ? [$result->home_score, $result->away_score]
                    : [$result->away_score, $result->home_score];

                $stats['played']++;
                $stats['scored'] += $for;
                $stats['conceded'] += $against;
                $stats[match (true) {
                    $for > $against => 'won', $for < $against => 'lost', default => 'draw'
                }]++;
            }

            $stats['goal_diff'] = $stats['scored'] - $stats['conceded'];
            $stats['rating'] = $stats['played'] > 0
                ? (int) round(($stats['won'] * 3 + $stats['draw']) / ($stats['played'] * 3) * 100)
                : null;

            return [...$team->toArray(), ...$stats];
        });

        return response()->json(
            $teams->sortBy([['rating', 'desc'], ['goal_diff', 'desc'], ['team_name', 'asc']])->values()
        );
    }

    public function players(Request $request, TournamentModel $tournament): JsonResponse
    {
        $players = PlayerModel::with('team')
            ->where('tour_id', $tournament->tour_id)
            ->when($request->query('search'), function ($query, string $search) {
                $query->where(fn ($q) => $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%"));
            })
            ->when($request->query('team_id'), fn ($query, string $teamId) => $query->where('team_id', $teamId))
            ->orderBy('first_name')
            ->get();

        return response()->json($players);
    }

    public function storePrediction(Request $request, TournamentModel $tournament): JsonResponse
    {
        $data = $request->validate([
            'first_place' => ['required', 'string'],
            'second_place' => ['required', 'string', 'different:first_place'],
            'third_place' => ['required', 'string', 'different:first_place', 'different:second_place'],
            'full_name' => ['required', 'string', 'max:100'],
            'phone_number' => ['required', 'string', 'regex:/^\+?[0-9 ]{7,20}$/'],
            'email' => ['nullable', 'email', 'max:100'],
        ], [
            'second_place.different' => 'Pick a different team for each position.',
            'third_place.different' => 'Pick a different team for each position.',
            'phone_number.regex' => 'Enter a valid phone number.',
        ]);

        $teamIds = [$data['first_place'], $data['second_place'], $data['third_place']];
        if ($tournament->relatedTeams()->whereKey($teamIds)->count() !== 3) {
            throw ValidationException::withMessages(['first_place' => 'Pick teams from this tournament.']);
        }

        $phone = preg_replace('/\D/', '', $data['phone_number']);
        if ($tournament->predictions()->where('phone_number', $phone)->exists()) {
            throw new ConflictHttpException('This phone number has already made a prediction.');
        }

        PredictionModel::create([
            ...$data,
            'phone_number' => $phone,
            'email' => $data['email'] ?? '',
            'tour_id' => $tournament->tour_id,
            'device_ip' => (string) $request->ip(),
            'created_at' => now(),
        ]);

        return response()->json(['message' => 'Prediction saved.'], 201);
    }

    public function storeFeedback(Request $request, TournamentModel $tournament): JsonResponse
    {
        $data = $request->validate([
            'name' => ['nullable', 'string', 'max:255'],
            'feedbackText' => ['required', 'string', 'max:2000'],
        ]);

        FeedbackModel::create([
            ...$data,
            'tour_id' => $tournament->tour_id,
            'device_ip' => (string) $request->ip(),
            'created_at' => now(),
        ]);

        return response()->json(['message' => 'Thanks for the feedback!'], 201);
    }
}
