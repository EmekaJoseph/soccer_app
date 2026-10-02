<?php

namespace App\Http\Controllers;

use App\Models\MatchModel;
use App\Models\SubUserModel;
use App\Models\TournamentModel;
use App\Models\UserModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

abstract class Controller
{
    protected function account(Request $request): UserModel|SubUserModel
    {
        return $request->user();
    }

    /**
     * Stop the request with a 404 unless the signed-in account manages the tournament.
     * (404 rather than 403 so ids of other people's tournaments are not confirmed.)
     */
    protected function authorizeTournament(Request $request, TournamentModel|string|null $tournament): TournamentModel
    {
        if (! $tournament instanceof TournamentModel) {
            $tournament = $tournament ? TournamentModel::find($tournament) : null;
        }

        abort_unless($this->account($request)->owns($tournament), 404, 'Tournament not found.');

        return $tournament;
    }

    /** Same as authorizeTournament() for any record that has a tour_id column. */
    protected function authorizeRecord(Request $request, Model $record): void
    {
        $this->authorizeTournament($request, (string) $record->getAttribute('tour_id'));
    }

    protected function authorizeMatch(Request $request, MatchModel $match): TournamentModel
    {
        return $this->authorizeTournament($request, $match->tour_id);
    }
}
