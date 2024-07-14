<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use stdClass;
use Carbon\Carbon;

use App\Models\TournamentModel;
use App\Models\Standings_LeagueModel;
use App\Models\TeamModel;
use App\Models\ResultModel;
use App\Models\MatchModel;

class Results_LeagueController extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function saveResult(Request $req)
    {
        // validate required varibles
        $rules = [
            // 'homeTeam' => 'required',
            // 'awayTeam' => 'required',
            // 'tour_id' => 'required',
            // 'date_played' => 'required',
            'homeTeam_score' => 'required',
            'awayTeam_score' => 'required',
            'match_id' => 'required'
        ];
        $validator = Validator::make($req->all(),  $rules);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }


        $match_id = $req->input('match_id');
        $match = MatchModel::find($match_id);

        $homeTeam = $match->home_team;
        $awayTeam = $match->away_team;
        $homeTeam_score = $req->input('homeTeam_score');
        $awayTeam_score = $req->input('awayTeam_score');
        $tour_id = $req->input('tour_id');
        $date_played = $req->input('date_played');

        // check if tournament exists
        $thisTournament = TournamentModel::find($tour_id);
        if (!$thisTournament) {
            return response()->json('invalid tournament', 203);
        }

        // check teams are valid
        if (sizeof(TeamModel::find([$homeTeam,  $awayTeam])) < 2) {
            return response()->json('Team(s) not found', 404);
        }


        // get teams already in the standings list
        $team1InStanding = Standings_LeagueModel::where('team_id', $homeTeam)->first();
        $team2InStanding = Standings_LeagueModel::where('team_id', $awayTeam)->first();

        // create new objects for both teams
        $team1 = new stdClass();
        $team2 = new stdClass();

        // update ids
        $team1->team_id = $homeTeam;
        $team2->team_id = $awayTeam;

        // update match played
        $team1->played =  $team1InStanding->played + 1;
        $team2->played =  $team2InStanding->played + 1;
        (TeamModel::find($homeTeam))->increment('match_played');
        (TeamModel::find($awayTeam))->increment('match_played');

        // update match won points and lost by higher score
        if ($homeTeam_score > $awayTeam_score) {
            $team1->points = $team1InStanding->points + 3;
            $team1->won = $team1InStanding->won + 1;
            $team2->lose =  $team2InStanding->lose + 1;
        } else if ($awayTeam_score > $homeTeam_score) {
            $team2->points =  $team2InStanding->points + 3;
            $team2->won =  $team2InStanding->won + 1;
            $team1->lose = $team1InStanding->lose + 1;
        } else {
            // update draws if no winner
            $team1->draw =  $team1InStanding->draw + 1;
            $team2->draw =  $team2InStanding->draw + 1;

            $team1->points = $team1InStanding->points + 1;
            $team2->points =  $team2InStanding->points + 1;
        }

        // update goal difference
        $diff1 = $homeTeam_score - $awayTeam_score;
        $diff2 = $awayTeam_score - $homeTeam_score;
        $team1->goal_diff = $team1InStanding->goal_diff + $diff1;
        $team2->goal_diff =  $team2InStanding->goal_diff + $diff2;


        // update tournament id
        $team1->tour_id = $team2->tour_id =  $tour_id;

        // update to standings
        Standings_LeagueModel::updateOrCreate(['team_id' => $team1->team_id], (array)$team1);
        Standings_LeagueModel::updateOrCreate(['team_id' => $team2->team_id], (array)$team2);

        // save current submission to results history
        $newReult = ResultModel::create([
            'home_team' => $homeTeam,
            'away_team' => $awayTeam,
            'home_score' => $homeTeam_score,
            'away_score' => $awayTeam_score,
            'tour_id' => $tour_id,
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
            'tour_id' => 'required',
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

        // get teams already in the standings list
        $team1InStanding = Standings_LeagueModel::where('team_id', $homeTeam)->first();
        $team2InStanding = Standings_LeagueModel::where('team_id', $awayTeam)->first();

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


        // update tournament id
        $team1->tour_id = $team2->tour_id =  $tour_id;

        // update to standings
        Standings_LeagueModel::updateOrCreate(['team_id' => $team1->team_id], (array)$team1);
        Standings_LeagueModel::updateOrCreate(['team_id' => $team2->team_id], (array)$team2);


        // delete result from results table
        ResultModel::find($result_id)->delete();

        return response()->json(['success' => 'updated successfully'], 200);
    }
}
