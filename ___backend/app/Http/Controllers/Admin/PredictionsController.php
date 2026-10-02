<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PredictionModel;
use App\Models\TournamentModel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class PredictionsController extends Controller
{
    public function index(Request $request, TournamentModel $tournament): JsonResponse
    {
        $this->authorizeTournament($request, $tournament);

        return response()->json($this->present($tournament, $tournament->predictions()->latest('created_at')->get()));
    }

    /**
     * Fans whose prediction matches the given finishing order. "second" and
     * "third" are optional ("0" or empty = any team).
     */
    public function winners(Request $request, TournamentModel $tournament): JsonResponse
    {
        $this->authorizeTournament($request, $tournament);

        $data = $request->validate([
            'first' => ['required', 'string'],
            'second' => ['nullable', 'string'],
            'third' => ['nullable', 'string'],
        ]);

        $predictions = $tournament->predictions()
            ->where('first_place', $data['first'])
            ->when(filled($data['second'] ?? null) && $data['second'] !== '0', fn ($q) => $q->where('second_place', $data['second']))
            ->when(filled($data['third'] ?? null) && $data['third'] !== '0', fn ($q) => $q->where('third_place', $data['third']))
            ->oldest('created_at') // earliest correct guess first
            ->get();

        return response()->json($this->present($tournament, $predictions));
    }

    /** Swap team ids for names (teams may have been deleted since). */
    private function present(TournamentModel $tournament, Collection $predictions): Collection
    {
        $names = $tournament->relatedTeams()->pluck('team_name', 'team_id');

        return $predictions->map(fn (PredictionModel $p) => [
            ...$p->toArray(),
            'first_place_id' => $p->first_place,
            'first_place' => $names[$p->first_place] ?? 'Removed team',
            'second_place' => $names[$p->second_place] ?? 'Removed team',
            'third_place' => $names[$p->third_place] ?? 'Removed team',
            'predicted' => Carbon::parse($p->created_at)->diffForHumans(),
        ]);
    }
}
