<?php

namespace App\Http\Controllers\Admin;

use App\Events\LiveMatchEnded;
use App\Events\LiveMatchStarted;
use App\Events\LiveMatchUpdated;
use App\Http\Controllers\Controller;
use App\Models\LiveMatchModel;
use App\Models\MatchModel;
use App\Models\TournamentModel;
use App\Services\MatchResultsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Throwable;

class LiveMatchesController extends Controller
{
    public function __construct(private readonly MatchResultsService $results) {}

    /** Live matches the signed-in account is scoring. */
    public function index(Request $request, TournamentModel $tournament): JsonResponse
    {
        $this->authorizeTournament($request, $tournament);

        $live = $tournament->liveMatches()
            ->where('creator', $this->account($request)->creatorKey())
            ->with(['homeTeam', 'awayTeam'])
            ->get()
            ->map(fn (LiveMatchModel $live) => $this->present($live));

        return response()->json($live);
    }

    /** Every live match in the tournament and who is scoring it (owner only). */
    public function all(Request $request, TournamentModel $tournament): JsonResponse
    {
        $this->authorizeTournament($request, $tournament);
        $me = $this->account($request);

        $live = $tournament->liveMatches()
            ->with(['homeTeam', 'awayTeam'])
            ->get()
            ->map(function (LiveMatchModel $live) use ($me) {
                $creator = $live->creatorAccount();

                return [
                    ...$this->present($live),
                    'creator' => $creator?->only(['email', 'firstname', 'lastname']),
                    'isMe' => $creator && $creator->email === $me->email ? 'You' : null,
                ];
            });

        return response()->json($live);
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate(['match_id' => ['required', 'string']]);

        $match = MatchModel::findOrFail($request->input('match_id'));
        $this->authorizeMatch($request, $match);

        if ($match->result()->exists()) {
            throw new ConflictHttpException('This match already has a result.');
        }

        if ($match->live()->exists()) {
            throw new ConflictHttpException('This match is already being scored live.');
        }

        $live = LiveMatchModel::create([
            'match_id' => $match->match_id,
            'creator' => $this->account($request)->creatorKey(),
            'tour_id' => $match->tour_id,
            'match_stage' => $match->match_stage,
            'home_team' => $match->home_team,
            'away_team' => $match->away_team,
            'home_team_score' => 0,
            'away_team_score' => 0,
            'curr_time' => 0,
            'isPaused' => false,
        ]);

        $this->broadcast(new LiveMatchStarted($live->tour_id, $live->live_id));

        return response()->json($this->present($live->load(['homeTeam', 'awayTeam'])), 201);
    }

    public function update(Request $request, LiveMatchModel $live): JsonResponse
    {
        $this->authorizeLive($request, $live);

        $data = $request->validate([
            'home_team_score' => ['required', 'integer', 'min:0', 'max:99'],
            'away_team_score' => ['required', 'integer', 'min:0', 'max:99'],
            'curr_time' => ['required', 'integer', 'min:0', 'max:200'],
            'isPaused' => ['sometimes', 'boolean'],
        ]);

        $live->update($data);

        $this->broadcast(new LiveMatchUpdated($live->tour_id, $live->live_id, [
            'home_team_score' => $live->home_team_score,
            'away_team_score' => $live->away_team_score,
            'curr_time' => $live->curr_time,
            'isPaused' => $live->isPaused,
        ]));

        return response()->json($this->present($live->load(['homeTeam', 'awayTeam'])));
    }

    /** End the live match, optionally saving its score as the final result. */
    public function end(Request $request, LiveMatchModel $live): JsonResponse
    {
        $this->authorizeLive($request, $live);
        $save = $request->boolean('save');

        $result = DB::transaction(function () use ($live, $save) {
            $result = null;

            if ($save) {
                $match = $live->match ?? abort(404, 'The scheduled match no longer exists.');
                $result = $this->results->saveResult($match, $live->home_team_score, $live->away_team_score);
            }

            $live->delete();

            return $result;
        });

        $this->broadcast(new LiveMatchEnded($live->tour_id, $live->live_id));

        return response()->json([
            'message' => $save ? 'Match ended and result saved.' : 'Match ended.',
            'result' => $result,
        ]);
    }

    /** Owners can manage any live match in their tournaments; sub-users only their own. */
    private function authorizeLive(Request $request, LiveMatchModel $live): void
    {
        $this->authorizeRecord($request, $live);
        $account = $this->account($request);

        abort_unless($account->isAdmin() || $live->creator === $account->creatorKey(), 403, 'Someone else is scoring this match.');
    }

    private function present(LiveMatchModel $live): array
    {
        return [
            ...$live->only(['live_id', 'match_id', 'tour_id', 'match_stage', 'home_team_score', 'away_team_score', 'curr_time', 'isPaused']),
            'home_team' => $live->homeTeam?->team_name,
            'away_team' => $live->awayTeam?->team_name,
        ];
    }

    /** Scores are already saved; a Pusher outage must not fail the request. */
    private function broadcast(object $event): void
    {
        try {
            event($event);
        } catch (Throwable $e) {
            Log::warning('Live score broadcast failed: '.$e->getMessage());
        }
    }
}
