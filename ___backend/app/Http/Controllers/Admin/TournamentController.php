<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TournamentModel;
use App\Models\UserModel;
use App\Support\ImageUploader;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;

class TournamentController extends Controller
{
    private const LOGO_FOLDER = 'tour_logos';

    public function __construct(private readonly ImageUploader $uploader) {}

    public function index(Request $request): JsonResponse
    {
        $tournaments = $this->account($request)->tournaments()
            ->withCount('relatedTeams as teams_count')
            ->latest()
            ->get()
            ->map(fn (TournamentModel $tour) => [
                ...$tour->toArray(),
                // Aliases kept for the dashboard table and tournament picker.
                'id' => $tour->tour_id,
                'title' => $tour->tour_title,
                'type' => $tour->tour_type,
                'created' => $tour->created_at?->diffForHumans(),
            ]);

        return response()->json($tournaments);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $this->validated($request);
        $account = $this->account($request);

        $this->ensureTitleIsFree($account->ownerId(), $data['tour_title']);

        $tournament = TournamentModel::create([
            'tour_title' => $data['tour_title'],
            'tour_type' => $data['tour_type'],
            'tour_desc' => $data['tour_desc'] ?? null,
            'tour_logo' => $request->hasFile('tour_logo')
                ? $this->uploader->store($request->file('tour_logo'), self::LOGO_FOLDER)
                : null,
            'user_id' => $account->ownerId(),
        ]);

        UserModel::whereKey($account->ownerId())
            ->increment($tournament->isCup() ? 'no_of_cups' : 'no_of_leagues');

        return response()->json($tournament, 201);
    }

    public function update(Request $request, TournamentModel $tournament): JsonResponse
    {
        $this->authorizeTournament($request, $tournament);
        $data = $this->validated($request, updating: true);

        $this->ensureTitleIsFree($tournament->user_id, $data['tour_title'], $tournament->tour_id);

        $newType = $data['tour_type'] ?? $tournament->tour_type;
        if ($newType !== $tournament->tour_type && $tournament->relatedTeams()->exists()) {
            throw ValidationException::withMessages([
                'tour_type' => 'The format cannot change once teams have been added.',
            ]);
        }

        $tournament->update([
            'tour_title' => $data['tour_title'],
            'tour_desc' => $data['tour_desc'] ?? null,
            'tour_type' => $newType,
            'tour_logo' => $this->uploader->replace($request->file('tour_logo'), $tournament->tour_logo, self::LOGO_FOLDER),
        ]);

        return response()->json($tournament);
    }

    public function destroy(Request $request, TournamentModel $tournament): JsonResponse
    {
        $this->authorizeTournament($request, $tournament);

        if ($tournament->relatedTeams()->exists()) {
            throw new ConflictHttpException('Delete the teams in this tournament first.');
        }

        $this->uploader->delete($tournament->tour_logo);
        $tournament->predictions()->delete();
        $tournament->feedback()->delete();
        $tournament->delete();

        UserModel::whereKey($tournament->user_id)
            ->where($tournament->isCup() ? 'no_of_cups' : 'no_of_leagues', '>', 0)
            ->decrement($tournament->isCup() ? 'no_of_cups' : 'no_of_leagues');

        return response()->json(['message' => 'Tournament deleted.']);
    }

    private function validated(Request $request, bool $updating = false): array
    {
        return $request->validate([
            'tour_title' => ['required', 'string', 'min:2', 'max:255'],
            'tour_type' => [$updating ? 'sometimes' : 'required', Rule::in([TournamentModel::TYPE_CUP, TournamentModel::TYPE_LEAGUE])],
            'tour_desc' => ['nullable', 'string', 'max:2000'],
            'tour_logo' => ['nullable', 'image', 'max:4096'],
        ]);
    }

    private function ensureTitleIsFree(int $ownerId, string $title, ?string $exceptId = null): void
    {
        $taken = TournamentModel::where('user_id', $ownerId)
            ->where('tour_title', $title)
            ->when($exceptId, fn ($q) => $q->whereKeyNot($exceptId))
            ->exists();

        if ($taken) {
            throw ValidationException::withMessages(['tour_title' => 'You already have a tournament with this name.']);
        }
    }
}
