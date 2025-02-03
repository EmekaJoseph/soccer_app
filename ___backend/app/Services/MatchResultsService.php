<?php

namespace App\Services;

use App\Interfaces\MatchResultsServiceInterface;
use App\Models\MatchModel;
use App\Models\Standings_CupModel;
use App\Models\ResultModel;
use App\Models\Standings_LeagueModel;
use App\Models\TeamModel;
use App\Models\TournamentModel;
use Exception;
use Illuminate\Support\Facades\Validator;

class MatchResultsService implements MatchResultsServiceInterface
{

    public function saveResult($req)
    {
        $match = MatchModel::find($req->match_id);
        $tour = TournamentModel::find($match->tour_id);
        $matchType = $tour->tour_type;
        if ($matchType == 'cup')  return $this->saveCupResult($req);
        else return $this->saveLeagueResult($req);
    }

    public function undoResult($req)
    {
        $result = ResultModel::find($req->result_id);
        $tour = TournamentModel::find($result->tour_id);
        $matchType = $tour->tour_type;
        if ($matchType == 'cup')  return $this->undoCupResult($req);
        else return $this->undoLeagueResult($req);
    }




    protected function saveCupResult($req)
    {
        try {
            // Validate required variables
            $validator = Validator::make($req->all(), [
                'match_id' => 'required',
                'homeTeam_score' => 'required',
                'awayTeam_score' => 'required',
                'home_score_pen' => 'nullable',
                'away_score_pen' => 'nullable',
                'match_stage' => 'nullable',
            ]);

            if ($validator->fails()) {
                return ['message' => $validator->errors(), 'status' => 422];;
            }

            // Find match
            $match = MatchModel::find($req->input('match_id'));
            if (!$match) return ['message' => 'Match not found', 'status' => 404];

            [$homeTeam, $awayTeam, $tour_id] = [$match->home_team, $match->away_team, $match->tour_id];

            // Process standings for group stage
            if ($match->match_stage === 'Group_Stage') {
                $teams = [
                    'home' => Standings_CupModel::firstOrNew(['team_id' => $homeTeam]),
                    'away' => Standings_CupModel::firstOrNew(['team_id' => $awayTeam]),
                ];

                foreach ($teams as $key => $team) {
                    $team->played += 1;
                }

                // Update match results
                if ($req->homeTeam_score > $req->awayTeam_score) {
                    $teams['home']->points += 3;
                    $teams['home']->won += 1;
                    $teams['away']->lose += 1;
                } elseif ($req->awayTeam_score > $req->homeTeam_score) {
                    $teams['away']->points += 3;
                    $teams['away']->won += 1;
                    $teams['home']->lose += 1;
                } else {
                    foreach ($teams as $team) {
                        $team->draw += 1;
                        $team->points += 1;
                    }
                }

                // Update goal difference
                $teams['home']->goal_diff += $req->homeTeam_score - $req->awayTeam_score;
                $teams['away']->goal_diff += $req->awayTeam_score - $req->homeTeam_score;

                foreach ($teams as $team) {
                    $team->save();
                }

                TeamModel::whereIn('team_id', [$homeTeam, $awayTeam])->increment('match_played');
            }

            // Save match result
            $newResult = ResultModel::create([
                'home_team' => $homeTeam,
                'away_team' => $awayTeam,
                'home_score' => $req->homeTeam_score,
                'away_score' => $req->awayTeam_score,
                'home_score_pen' => $req->input('home_score_pen'),
                'away_score_pen' => $req->input('away_score_pen'),
                'match_id' => $match->match_id,
                'match_stage' => $req->input('match_stage'),
                'tour_id' => $tour_id,
                'date_played' => $match->kick_off,
            ]);

            return $newResult;
        } catch (\Throwable $th) {
            return ['message' => $th->getMessage(), 'status' => 500];
            // throw $th;
        }
    }

    protected function undoCupResult($req)
    {
        try {
            // Validate required variables
            $rules = [
                'result_id' => 'required'
            ];

            $validator = Validator::make($req->all(), $rules);

            if ($validator->fails()) {
                return ['message' => $validator->errors(), 'status' => 422];;
            }

            $result_id = $req->input('result_id');
            $result = ResultModel::find($result_id);
            $match = MatchModel::find($result->match_id);

            [$homeTeam, $awayTeam] = [$result->home_team, $result->away_team];


            if ($match->match_stage === 'Group_Stage') {
                $teams = [
                    'home' => Standings_CupModel::where('team_id', $homeTeam)->first(),
                    'away' => Standings_CupModel::where('team_id', $awayTeam)->first(),
                ];

                if ($teams['home']) $teams['home']->played -= 1;
                if ($teams['away']) $teams['away']->played -= 1;

                if ($result->home_score > $result->away_score) {
                    if ($teams['home']) {
                        $teams['home']->points -= 3;
                        $teams['home']->won -= 1;
                    }
                    if ($teams['away']) $teams['away']->lose -= 1;
                } elseif ($result->away_score > $result->home_score) {
                    if ($teams['away']) {
                        $teams['away']->points -= 3;
                        $teams['away']->won -= 1;
                    }
                    if ($teams['home']) $teams['home']->lose -= 1;
                } else {
                    if ($teams['home']) {
                        $teams['home']->points -= 1;
                        $teams['home']->draw -= 1;
                    }
                    if ($teams['away']) {
                        $teams['away']->points -= 1;
                        $teams['away']->draw -= 1;
                    }
                }

                // Revert goal difference
                if ($teams['home']) $teams['home']->goal_diff -= ($result->home_score - $result->away_score);
                if ($teams['away']) $teams['away']->goal_diff -= ($result->away_score - $result->home_score);

                foreach ($teams as $team) {
                    if ($team) $team->save();
                }

                // Decrement match_played count
                TeamModel::whereIn('team_id', [$homeTeam, $awayTeam])->decrement('match_played');
            }

            // Delete the recorded result
            $result->delete();

            return 'Match result undone successfully';
        } catch (\Throwable $th) {
            return ['message' => $th->getMessage(), 'status' => 500];
            //throw $th;
        }
    }



    protected function saveLeagueResult($req)
    {
        // Validate required variables
        $validator = Validator::make($req->all(), [
            'match_id' => 'required',
            'homeTeam_score' => 'required',
            'awayTeam_score' => 'required',
            'home_score_pen' => 'nullable',
            'away_score_pen' => 'nullable',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Find match
        $match = MatchModel::find($req->input('match_id'));
        if (!$match) {
            return response()->json('Match not found', 404);
        }

        // Extract match details
        $homeTeam = $match->home_team;
        $awayTeam = $match->away_team;
        $tour_id = $match->tour_id;
        $homeTeam_score = $req->input('homeTeam_score');
        $awayTeam_score = $req->input('awayTeam_score');

        // Get standings for both teams
        $teams = [
            'home' => Standings_LeagueModel::firstOrNew(['team_id' => $homeTeam]),
            'away' => Standings_LeagueModel::firstOrNew(['team_id' => $awayTeam]),
        ];

        // Increment matches played for both teams
        TeamModel::whereIn('team_id', [$homeTeam, $awayTeam])->increment('match_played');

        // Initialize match result for both teams
        foreach ($teams as $team) {
            $team->played++;
            $team->goal_diff = $team->goal_diff ?? 0;
        }

        // Determine result: win, lose, or draw
        if ($homeTeam_score > $awayTeam_score) {
            $teams['home']->points += 3;
            $teams['home']->won++;
            $teams['away']->lose++;
        } elseif ($awayTeam_score > $homeTeam_score) {
            $teams['away']->points += 3;
            $teams['away']->won++;
            $teams['home']->lose++;
        } else {
            $teams['home']->draw++;
            $teams['away']->draw++;
            $teams['home']->points++;
            $teams['away']->points++;
        }

        // Calculate goal difference
        $teams['home']->goal_diff += $homeTeam_score - $awayTeam_score;
        $teams['away']->goal_diff += $awayTeam_score - $homeTeam_score;

        // Save or update standings
        foreach ($teams as $team) {
            $team->save();
        }

        // Create and return match result
        $newResult = ResultModel::create([
            'home_team' => $homeTeam,
            'away_team' => $awayTeam,
            'home_score' => $homeTeam_score,
            'away_score' => $awayTeam_score,
            'home_score_pen' => $req->input('home_score_pen'),
            'away_score_pen' => $req->input('away_score_pen'),
            'match_id' => $match->match_id,
            'tour_id' => $tour_id,
            'date_played' => $match->kick_off,
        ]);

        return response()->json($newResult, 200);
    }

    protected function undoLeagueResult($req)
    {
        // Validate required variables
        $validator = Validator::make($req->all(), [
            'match_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Find match
        $match = MatchModel::find($req->input('match_id'));
        if (!$match) {
            return response()->json('Match not found', 404);
        }

        // Extract match details
        $homeTeam = $match->home_team;
        $awayTeam = $match->away_team;
        $tour_id = $match->tour_id;

        // Get standings for both teams
        $teams = [
            'home' => Standings_LeagueModel::where('team_id', $homeTeam)->first(),
            'away' => Standings_LeagueModel::where('team_id', $awayTeam)->first(),
        ];

        if (!$teams['home'] || !$teams['away']) {
            return response()->json('One or both teams not found in standings', 404);
        }

        // Undo matches played increment
        TeamModel::whereIn('team_id', [$homeTeam, $awayTeam])->decrement('match_played');

        // Reset match results (points, won, lost, draw, goal difference)
        $teams['home']->played--;
        $teams['away']->played--;

        // Undo result: win, lose, or draw
        $homeTeam_score = $req->input('homeTeam_score');
        $awayTeam_score = $req->input('awayTeam_score');

        if ($homeTeam_score > $awayTeam_score) {
            $teams['home']->points -= 3;
            $teams['home']->won--;
            $teams['away']->lose--;
        } elseif ($awayTeam_score > $homeTeam_score) {
            $teams['away']->points -= 3;
            $teams['away']->won--;
            $teams['home']->lose--;
        } else {
            $teams['home']->draw--;
            $teams['away']->draw--;
            $teams['home']->points--;
            $teams['away']->points--;
        }

        // Undo goal difference
        $teams['home']->goal_diff -= ($homeTeam_score - $awayTeam_score);
        $teams['away']->goal_diff -= ($awayTeam_score - $homeTeam_score);

        // Save the updated standings
        $teams['home']->save();
        $teams['away']->save();

        // Delete the result record (assuming you want to delete it)
        ResultModel::where('match_id', $req->input('match_id'))->delete();

        return response()->json('Match result successfully undone', 200);
    }
}
