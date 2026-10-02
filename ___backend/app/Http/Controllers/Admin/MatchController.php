<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MatchModel;
use App\Models\TeamModel;
use App\Models\TournamentModel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;

class MatchController extends Controller
{
    public function index(Request $request, TournamentModel $tournament): JsonResponse
    {
        $this->authorizeTournament($request, $tournament);

        return response()->json(
            $tournament->matches()
                ->with(['homeTeam', 'awayTeam', 'result', 'live'])
                ->orderBy('kick_off')
                ->get()
        );
    }

    public function store(Request $request): JsonResponse
    {
        $tournament = $this->authorizeTournament($request, $request->input('tour_id'));

        $data = $request->validate([
            'tour_id' => ['required', 'string'],
            'homeTeam' => ['required', 'string'],
            'awayTeam' => ['required', 'string', 'different:homeTeam'],
            'kick_off' => ['required', 'date'],
            'venue' => ['required', 'string', 'max:255'],
            'match_stage' => [$tournament->isCup() ? 'required' : 'nullable', Rule::in(MatchModel::STAGES)],
        ], ['awayTeam.different' => 'A team cannot play itself.']);

        $teamsInTournament = TeamModel::where('tour_id', $tournament->tour_id)
            ->whereKey([$data['homeTeam'], $data['awayTeam']])
            ->count();

        if ($teamsInTournament !== 2) {
            throw ValidationException::withMessages(['homeTeam' => 'Both teams must belong to this tournament.']);
        }

        $kickOff = Carbon::parse($data['kick_off'])->toIso8601ZuluString('millisecond');

        $duplicate = MatchModel::where([
            'home_team' => $data['homeTeam'],
            'away_team' => $data['awayTeam'],
            'kick_off' => $kickOff,
        ])->exists();

        if ($duplicate) {
            throw new ConflictHttpException('This match is already scheduled.');
        }

        $match = MatchModel::create([
            'tour_id' => $tournament->tour_id,
            'home_team' => $data['homeTeam'],
            'away_team' => $data['awayTeam'],
            'venue' => $data['venue'],
            'kick_off' => $kickOff,
            'match_stage' => $tournament->isCup() ? $data['match_stage'] : null,
            'created' => now()->toDateTimeString(),
        ]);

        return response()->json($match->load(['homeTeam', 'awayTeam']), 201);
    }

    public function update(Request $request, MatchModel $match): JsonResponse
    {
        $tournament = $this->authorizeMatch($request, $match);

        $data = $request->validate([
            'kick_off' => ['required', 'date'],
            'venue' => ['required', 'string', 'max:255'],
            'match_stage' => ['nullable', Rule::in(MatchModel::STAGES)],
        ]);

        // The stage decides whether a result touched the standings, so freeze it once played.
        if ($match->result()->exists() && ($data['match_stage'] ?? $match->match_stage) !== $match->match_stage) {
            throw ValidationException::withMessages(['match_stage' => 'Undo the result before changing the stage.']);
        }

        $match->update([
            'venue' => $data['venue'],
            'kick_off' => Carbon::parse($data['kick_off'])->toIso8601ZuluString('millisecond'),
            'match_stage' => $tournament->isCup() ? ($data['match_stage'] ?? $match->match_stage) : null,
        ]);

        return response()->json($match->load(['homeTeam', 'awayTeam', 'result']));
    }

    public function destroy(Request $request, MatchModel $match): JsonResponse
    {
        $this->authorizeMatch($request, $match);

        if ($match->result()->exists()) {
            throw new ConflictHttpException('Undo this match\'s result before deleting it.');
        }

        $match->live()->delete();
        $match->delete();

        return response()->json(['message' => 'Match deleted.']);
    }
}
