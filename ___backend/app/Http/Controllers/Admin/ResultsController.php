<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\PublicViewController;
use App\Models\MatchModel;
use App\Models\ResultModel;
use App\Models\TournamentModel;
use App\Services\MatchResultsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ResultsController extends Controller
{
    public function __construct(private readonly MatchResultsService $results) {}

    public function index(Request $request, TournamentModel $tournament): JsonResponse
    {
        $this->authorizeTournament($request, $tournament);

        return app(PublicViewController::class)->results($tournament);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'match_id' => ['required', 'string'],
            'homeTeam_score' => ['required', 'integer', 'min:0', 'max:99'],
            'awayTeam_score' => ['required', 'integer', 'min:0', 'max:99'],
            'home_score_pen' => ['nullable', 'integer', 'min:0', 'max:99'],
            'away_score_pen' => ['nullable', 'integer', 'min:0', 'max:99'],
        ]);

        $match = MatchModel::findOrFail($data['match_id']);
        $this->authorizeMatch($request, $match);

        $result = $this->results->saveResult(
            $match,
            (int) $data['homeTeam_score'],
            (int) $data['awayTeam_score'],
            isset($data['home_score_pen']) ? (int) $data['home_score_pen'] : null,
            isset($data['away_score_pen']) ? (int) $data['away_score_pen'] : null,
        );

        return response()->json($result, 201);
    }

    public function destroy(Request $request, ResultModel $result): JsonResponse
    {
        $this->authorizeRecord($request, $result);

        $this->results->undoResult($result);

        return response()->json(['message' => 'Result undone.']);
    }
}
