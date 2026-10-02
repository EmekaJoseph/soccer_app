<?php

namespace App\Services;

use App\Models\MatchModel;
use App\Models\ResultModel;
use App\Models\Standings_CupModel;
use App\Models\Standings_LeagueModel;
use App\Models\TeamModel;
use App\Models\TournamentModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;

/**
 * Records final scores and keeps the standings tables in step.
 *
 * League tournaments: every result counts towards the table.
 * Cup tournaments: only Group_Stage results count towards the group tables;
 * knock-out games are just recorded (optionally with a penalty shoot-out).
 */
class MatchResultsService
{
    public function saveResult(
        MatchModel $match,
        int $homeScore,
        int $awayScore,
        ?int $homePens = null,
        ?int $awayPens = null,
    ): ResultModel {
        if ($match->result()->exists()) {
            throw new ConflictHttpException('A result has already been saved for this match.');
        }

        $hasPens = $homePens !== null || $awayPens !== null;

        if ($hasPens) {
            $errors = match (true) {
                ! $match->allowsPenalties() => 'Penalties only apply to knock-out matches.',
                $homeScore !== $awayScore => 'Penalties only apply when the match ends in a draw.',
                $homePens === null || $awayPens === null => 'Enter the penalty score for both teams.',
                $homePens === $awayPens => 'A penalty shoot-out must have a winner.',
                default => null,
            };

            if ($errors) {
                throw ValidationException::withMessages(['home_score_pen' => $errors]);
            }
        }

        return DB::transaction(function () use ($match, $homeScore, $awayScore, $homePens, $awayPens) {
            $tournament = $match->relatedTournament;

            if ($this->countsTowardsStandings($tournament, $match)) {
                $this->applyToStandings($tournament, $match->home_team, $match->away_team, $homeScore, $awayScore, 1);
            }

            TeamModel::whereIn('team_id', [$match->home_team, $match->away_team])->increment('match_played');

            return ResultModel::create([
                'match_id' => $match->match_id,
                'tour_id' => $match->tour_id,
                'home_team' => $match->home_team,
                'away_team' => $match->away_team,
                'home_score' => $homeScore,
                'away_score' => $awayScore,
                'home_score_pen' => $homePens,
                'away_score_pen' => $awayPens,
                'match_stage' => $match->match_stage,
                'date_played' => $match->kick_off,
            ]);
        });
    }

    public function undoResult(ResultModel $result): void
    {
        DB::transaction(function () use ($result) {
            $tournament = $result->relatedTournament;
            $match = $result->relatedMatch;

            // The match may have been deleted since; fall back to the stage stored on the result.
            $stage = $match?->match_stage ?? $result->match_stage;

            if (! $tournament->isCup() || $stage === MatchModel::GROUP_STAGE) {
                $this->applyToStandings(
                    $tournament, $result->home_team, $result->away_team,
                    $result->home_score, $result->away_score, -1,
                );
            }

            TeamModel::whereIn('team_id', [$result->home_team, $result->away_team])
                ->where('match_played', '>', 0)
                ->decrement('match_played');

            $result->delete();
        });
    }

    private function countsTowardsStandings(TournamentModel $tournament, MatchModel $match): bool
    {
        return ! $tournament->isCup() || $match->match_stage === MatchModel::GROUP_STAGE;
    }

    /**
     * Add ($direction = 1) or remove ($direction = -1) one result from both teams' rows.
     */
    private function applyToStandings(
        TournamentModel $tournament,
        string $homeTeam,
        string $awayTeam,
        int $homeScore,
        int $awayScore,
        int $direction,
    ): void {
        $model = $tournament->isCup() ? Standings_CupModel::class : Standings_LeagueModel::class;

        foreach ([[$homeTeam, $homeScore, $awayScore], [$awayTeam, $awayScore, $homeScore]] as [$teamId, $for, $against]) {
            $row = $model::firstOrNew(['team_id' => $teamId, 'tour_id' => $tournament->tour_id]);

            if (! $row->exists && $tournament->isCup()) {
                $row->group_in = TeamModel::find($teamId)?->group_in;
            }

            $row->played += $direction;
            $row->goal_diff += $direction * ($for - $against);

            if ($for > $against) {
                $row->won += $direction;
                $row->points += $direction * 3;
            } elseif ($for < $against) {
                $row->lose += $direction;
            } else {
                $row->draw += $direction;
                $row->points += $direction;
            }

            $row->save();
        }
    }
}
