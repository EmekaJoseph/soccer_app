<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Validator;

use Illuminate\Http\Request;
use Carbon\Carbon;

use App\Models\TournamentModel;
use App\Models\TeamModel;
use App\Models\Standings_CupModel;
use App\Models\ResultModel;

use stdClass;

class Results_CupController extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function saveResult(Request $req)
    {
        // validate required varibles
        $rules = [
            'homeTeam' => 'required',
            'awayTeam' => 'required',
            'homeTeam_score' => 'required',
            'awayTeam_score' => 'required',
            'tour_id' => 'required',
            'match_stage' => 'required',
            'date_played' => 'required'
        ];
        $validator = Validator::make($req->all(),  $rules);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // get the teams scores
        $homeTeam = $req->input('homeTeam');
        $awayTeam = $req->input('awayTeam');
        $homeTeam_score = $req->input('homeTeam_score');
        $awayTeam_score = $req->input('awayTeam_score');
        $tour_id = $req->input('tour_id');
        $match_stage = $req->input('match_stage');
        $date_played = $req->input('date_played');
        $group_in = $req->input('group_in');

        // check if tournament exists
        $thisTournament = TournamentModel::find($tour_id);
        if (!$thisTournament) {
            return response()->json('invalid tournament', 203);
        }

        // check teams are valid
        if (sizeof(TeamModel::find([$homeTeam,  $awayTeam])) < 2) {
            return response()->json('Team(s) not found', 404);
        }

        // if result is in groupstage, update to standings
        if ($match_stage == 'Group_Stage') {

            // get teams already in the standings list
            $team1InStanding = Standings_CupModel::where('team_id', $homeTeam)->first();
            $team2InStanding = Standings_CupModel::where('team_id', $awayTeam)->first();

            // create new objects for both teams
            $team1 = new stdClass();
            $team2 = new stdClass();

            // update ids
            $team1->team_id = $homeTeam;
            $team2->team_id = $awayTeam;

            // update match played
            $team1->played = $team1InStanding ? $team1InStanding->played + 1 : 1;
            $team2->played = $team2InStanding ? $team2InStanding->played + 1 : 1;
            (TeamModel::find($homeTeam))->increment('match_played');
            (TeamModel::find($awayTeam))->increment('match_played');

            // update match won points and lost by higher score
            if ($homeTeam_score > $awayTeam_score) {
                $team1->points = $team1InStanding ? $team1InStanding->points + 3 : 3;
                $team1->won = $team1InStanding ? $team1InStanding->won + 1 : 1;
                $team2->lose = $team2InStanding ? $team2InStanding->lose + 1 : 1;
            } else if ($awayTeam_score > $homeTeam_score) {
                $team2->points = $team2InStanding ? $team2InStanding->points + 3 : 3;
                $team2->won = $team2InStanding ? $team2InStanding->won + 1 : 1;
                $team1->lose = $team1InStanding ? $team1InStanding->lose + 1 : 1;
            } else {
                // update draws if no winner
                $team1->draw = $team1InStanding ? $team1InStanding->draw + 1 : 1;
                $team2->draw = $team2InStanding ? $team2InStanding->draw + 1 : 1;

                $team1->points = $team1InStanding ? $team1InStanding->points + 1 : 1;
                $team2->points = $team2InStanding ? $team2InStanding->points + 1 : 1;
            }

            // update goal difference
            $diff1 = $homeTeam_score - $awayTeam_score;
            $diff2 = $awayTeam_score - $homeTeam_score;
            $team1->goal_diff = $team1InStanding ? $team1InStanding->goal_diff + $diff1 : $diff1;
            $team2->goal_diff = $team2InStanding ? $team2InStanding->goal_diff + $diff2 : $diff2;


            // update tournament id
            $team1->tour_id = $team2->tour_id =  $tour_id;

            // update team_group
            $team1->group_in = $team2->group_in =  $group_in;

            // update to standings
            Standings_CupModel::updateOrCreate(['team_id' => $team1->team_id], (array)$team1);
            Standings_CupModel::updateOrCreate(['team_id' => $team2->team_id], (array)$team2);
        }

        // save current submission to results history
        $newReult = ResultModel::create([
            'home_team' => $homeTeam,
            'away_team' => $awayTeam,
            'home_score' => $homeTeam_score,
            'away_score' => $awayTeam_score,
            'home_score_pen' => $req->input('home_score_pen', null),
            'away_score_pen' => $req->input('away_score_pen', null),
            'tour_id' => $tour_id,
            'match_stage' => $match_stage,
            'date_played' => (Carbon::parse($date_played))->toDateString()
        ]);

        return response()->json($newReult, 200);
    }




    public function undoSaveResult(Request $req, $result_id)
    {
        // validate required varibles
        $rules = [
            'homeTeam' => 'required',
            'awayTeam' => 'required',
            'homeTeam_score' => 'required',
            'awayTeam_score' => 'required',
            'match_stage' => 'required',
        ];
        $validator = Validator::make($req->all(),  $rules);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // get the teams scores
        $homeTeam = $req->input('homeTeam');
        $awayTeam = $req->input('awayTeam');
        $homeTeam_score = $req->input('homeTeam_score');
        $awayTeam_score = $req->input('awayTeam_score');
        $match_stage = $req->input('match_stage');

        // if result is in groupstage, update to standings
        if ($match_stage == 'Group_Stage') {

            // get teams if already in the standings list
            $team1InStanding = Standings_CupModel::where('team_id', $homeTeam)->first();
            $team2InStanding = Standings_CupModel::where('team_id', $awayTeam)->first();

            // create new objects for both teams
            $team1 = new stdClass();
            $team2 = new stdClass();

            // update ids
            $team1->team_id = $homeTeam;
            $team2->team_id = $awayTeam;

            // update match played
            $team1->played =  $team1InStanding->played - 1;
            $team2->played =  $team2InStanding->played - 1;
            (TeamModel::find($homeTeam))->increment('match_played', '-1');
            (TeamModel::find($awayTeam))->increment('match_played', '-1');

            // update match won points and lost by higher score
            if ($homeTeam_score > $awayTeam_score) {
                $team1->points =  $team1InStanding->points - 3;
                $team1->won = $team1InStanding->won - 1;
                $team2->lose = $team2InStanding->lose - 1;
            } else if ($awayTeam_score > $homeTeam_score) {
                $team2->points = $team2InStanding->points - 3;
                $team2->won = $team2InStanding->won - 1;
                $team1->lose =  $team1InStanding->lose - 1;
            } else {
                // update draws if no winner
                $team1->draw =  $team1InStanding->draw - 1;
                $team2->draw = $team2InStanding->draw - 1;

                $team1->points = $team1InStanding->points - 1;
                $team2->points = $team2InStanding->points - 1;
            }

            // update goal difference
            $diff1 = $homeTeam_score - $awayTeam_score;
            $diff2 = $awayTeam_score - $homeTeam_score;
            $team1->goal_diff =  $team1InStanding->goal_diff - $diff1;
            $team2->goal_diff =  $team2InStanding->goal_diff - $diff2;

            // update to standings
            Standings_CupModel::updateOrCreate(['team_id' => $team1->team_id], (array)$team1);
            Standings_CupModel::updateOrCreate(['team_id' => $team2->team_id], (array)$team2);
        }
        // delete result from results table
        ResultModel::find($result_id)->delete();

        return response()->json(['success' => 'updated successfully'], 200);
    }
}
