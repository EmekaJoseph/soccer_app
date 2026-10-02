<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LiveMatchModel;
use App\Models\MatchModel;
use App\Models\ResultModel;
use App\Models\Standings_CupModel;
use App\Models\Standings_LeagueModel;
use App\Models\TeamModel;
use App\Models\TournamentModel;
use App\Support\ImageUploader;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class TeamsController extends Controller
{
    public const GROUPS = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L'];

    private const BADGE_FOLDER = 'team_badges';

    public function __construct(private readonly ImageUploader $uploader) {}

    public function index(Request $request, TournamentModel $tournament): JsonResponse
    {
        $this->authorizeTournament($request, $tournament);

        return response()->json(
            $tournament->relatedTeams()->withCount('players')->orderBy('team_name')->get()
        );
    }

    public function store(Request $request): JsonResponse
    {
        $tournament = $this->authorizeTournament($request, $request->input('tour_id'));
        $data = $this->validated($request, $tournament);
        $this->ensureNameIsFree($tournament, $data['team_name']);

        $team = DB::transaction(function () use ($request, $tournament, $data) {
            $team = TeamModel::create([
                ...$data,
                'group_in' => $tournament->isCup() ? $data['group_in'] : null,
                'team_badge' => $request->hasFile('team_badge')
                    ? $this->uploader->store($request->file('team_badge'), self::BADGE_FOLDER, 120)
                    : null,
            ]);

            $tournament->standings()->create([
                'team_id' => $team->team_id,
                'group_in' => $team->group_in,
            ]);

            return $team;
        });

        return response()->json($team, 201);
    }

    public function update(Request $request, TeamModel $team): JsonResponse
    {
        $tournament = $this->authorizeTournament($request, $team->tour_id);
        $data = $this->validated($request, $tournament);
        $this->ensureNameIsFree($tournament, $data['team_name'], $team->team_id);

        DB::transaction(function () use ($request, $tournament, $team, $data) {
            $team->update([
                ...$data,
                'tour_id' => $team->tour_id,
                'group_in' => $tournament->isCup() ? $data['group_in'] : null,
                'team_badge' => $this->uploader->replace($request->file('team_badge'), $team->team_badge, self::BADGE_FOLDER, 120),
            ]);

            if ($tournament->isCup()) {
                Standings_CupModel::where('team_id', $team->team_id)->update(['group_in' => $team->group_in]);
            }
        });

        return response()->json($team->loadCount('players'));
    }

    public function destroy(Request $request, TeamModel $team): JsonResponse
    {
        $this->authorizeRecord($request, $team);

        if (ResultModel::where('home_team', $team->team_id)->orWhere('away_team', $team->team_id)->exists()) {
            throw ValidationException::withMessages([
                'team' => 'This team has recorded results. Undo them first so the standings stay correct.',
            ]);
        }

        DB::transaction(function () use ($team) {
            foreach ($team->players as $player) {
                $this->uploader->delete($player->image);
            }
            $team->players()->delete();

            Standings_CupModel::where('team_id', $team->team_id)->delete();
            Standings_LeagueModel::where('team_id', $team->team_id)->delete();
            MatchModel::where('home_team', $team->team_id)->orWhere('away_team', $team->team_id)->delete();
            LiveMatchModel::where('home_team', $team->team_id)->orWhere('away_team', $team->team_id)->delete();

            $this->uploader->delete($team->team_badge);
            $team->delete();
        });

        return response()->json(['message' => 'Team deleted.']);
    }

    private function validated(Request $request, TournamentModel $tournament): array
    {
        return $request->validate([
            'tour_id' => ['required', 'string'],
            'team_name' => ['required', 'string', 'max:255'],
            'team_brief' => ['nullable', 'string', 'max:5000'],
            'team_color' => ['nullable', 'string', 'max:30'],
            'manager' => ['nullable', 'string', 'max:100'],
            'address' => ['nullable', 'string', 'max:255'],
            'group_in' => [$tournament->isCup() ? 'required' : 'nullable', Rule::in(self::GROUPS)],
            'team_badge' => ['nullable', 'image', 'max:4096'],
        ], ['group_in.required' => 'Pick the group this team plays in.']);
    }

    private function ensureNameIsFree(TournamentModel $tournament, string $name, ?string $exceptId = null): void
    {
        $taken = $tournament->relatedTeams()
            ->where('team_name', $name)
            ->when($exceptId, fn ($q) => $q->whereKeyNot($exceptId))
            ->exists();

        if ($taken) {
            throw ValidationException::withMessages(['team_name' => 'A team with this name already exists in the tournament.']);
        }
    }
}
