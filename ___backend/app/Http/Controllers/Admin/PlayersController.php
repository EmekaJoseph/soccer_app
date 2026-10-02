<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\PublicViewController;
use App\Models\PlayerModel;
use App\Models\TeamModel;
use App\Models\TournamentModel;
use App\Support\ImageUploader;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class PlayersController extends Controller
{
    private const PHOTO_FOLDER = 'players_img';

    public function __construct(private readonly ImageUploader $uploader) {}

    public function index(Request $request, TournamentModel $tournament): JsonResponse
    {
        $this->authorizeTournament($request, $tournament);

        return app(PublicViewController::class)->players($request, $tournament);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $this->validated($request);
        $team = $this->authorizedTeam($request, $data['team_id']);

        $player = PlayerModel::create([
            ...$data,
            'tour_id' => $team->tour_id,
            'image' => $request->hasFile('image')
                ? $this->uploader->store($request->file('image'), self::PHOTO_FOLDER)
                : null,
        ]);

        return response()->json($player->load('team'), 201);
    }

    public function update(Request $request, PlayerModel $player): JsonResponse
    {
        $this->authorizeRecord($request, $player);
        $data = $this->validated($request);
        $team = $this->authorizedTeam($request, $data['team_id']);

        if ($team->tour_id !== $player->tour_id) {
            throw ValidationException::withMessages(['team_id' => 'Players can only move between teams in the same tournament.']);
        }

        $player->update([
            ...$data,
            'image' => $this->uploader->replace($request->file('image'), $player->image, self::PHOTO_FOLDER),
        ]);

        return response()->json($player->load('team'));
    }

    public function destroy(Request $request, PlayerModel $player): JsonResponse
    {
        $this->authorizeRecord($request, $player);

        $this->uploader->delete($player->image);
        $player->delete();

        return response()->json(['message' => 'Player deleted.']);
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'team_id' => ['required', 'string'],
            'dob' => ['nullable', 'date', 'before:today'],
            'info' => ['nullable', 'string', 'max:50'],
            'image' => ['nullable', 'image', 'max:4096'],
        ]);
    }

    private function authorizedTeam(Request $request, string $teamId): TeamModel
    {
        $team = TeamModel::find($teamId);

        if (! $team || ! $this->account($request)->owns($team->relatedTournament)) {
            throw ValidationException::withMessages(['team_id' => 'Pick a team from your tournament.']);
        }

        return $team;
    }
}
