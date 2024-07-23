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
        // Validate required variables
        $rules = [
            'match_id' => 'required',
            'homeTeam_score' => 'required',
            'awayTeam_score' => 'required',
            'home_score_pen' => 'nullable',
            'away_score_pen' => 'nullable',
        ];

        $validator = Validator::make($req->all(), $rules);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $match_id = $req->input('match_id');
        $match = MatchModel::find($match_id);

        if (!$match) {
            return response()->json('Match not found', 404);
        }

        $homeTeam = $match->home_team;
        $awayTeam = $match->away_team;
        $homeTeam_score = $req->input('homeTeam_score');
        $awayTeam_score = $req->input('awayTeam_score');
        $tour_id = $match->tour_id;


        $team1InStanding = Standings_LeagueModel::where('team_id', $homeTeam)->first();
        $team2InStanding = Standings_LeagueModel::where('team_id', $awayTeam)->first();

        $team1_diff = [
            'team_id' => $homeTeam,
            'tour_id' => $tour_id,
            'played' => $team1InStanding ? $team1InStanding->played + 1 : 1,
            'points' => $team1InStanding ? $team1InStanding->points : 0,
            'won' => $team1InStanding ? $team1InStanding->won : 0,
            'lose' => $team1InStanding ? $team1InStanding->lose : 0,
            'draw' => $team1InStanding ? $team1InStanding->draw : 0,
            'goal_diff' => $team1InStanding ? $team1InStanding->goal_diff : 0
        ];

        $team2_diff = [
            'team_id' => $awayTeam,
            'tour_id' => $tour_id,
            'played' => $team2InStanding ? $team2InStanding->played + 1 : 1,
            'points' => $team2InStanding ? $team2InStanding->points : 0,
            'won' => $team2InStanding ? $team2InStanding->won : 0,
            'lose' => $team2InStanding ? $team2InStanding->lose : 0,
            'draw' => $team2InStanding ? $team2InStanding->draw : 0,
            'goal_diff' => $team2InStanding ? $team2InStanding->goal_diff : 0
        ];

        TeamModel::find($homeTeam)->increment('match_played');
        TeamModel::find($awayTeam)->increment('match_played');

        if ($homeTeam_score > $awayTeam_score) {
            $team1_diff['points'] += 3;
            $team1_diff['won'] += 1;
            $team2_diff['lose'] += 1;
        } elseif ($awayTeam_score > $homeTeam_score) {
            $team2_diff['points'] += 3;
            $team2_diff['won'] += 1;
            $team1_diff['lose'] += 1;
        } else {
            $team1_diff['draw'] += 1;
            $team2_diff['draw'] += 1;
            $team1_diff['points'] += 1;
            $team2_diff['points'] += 1;
        }

        $diff1 = $homeTeam_score - $awayTeam_score;
        $diff2 = $awayTeam_score - $homeTeam_score;
        $team1_diff['goal_diff'] += $diff1;
        $team2_diff['goal_diff'] += $diff2;

        Standings_LeagueModel::updateOrCreate(['team_id' => $homeTeam], $team1_diff);
        Standings_LeagueModel::updateOrCreate(['team_id' => $awayTeam], $team2_diff);

        $newResult = ResultModel::create([
            'home_team' => $homeTeam,
            'away_team' => $awayTeam,
            'home_score' => $homeTeam_score,
            'away_score' => $awayTeam_score,
            'home_score_pen' => $req->input('home_score_pen', null),
            'away_score_pen' => $req->input('away_score_pen', null),
            'match_id' => $match_id,
            'tour_id' => $tour_id,
            'date_played' => $match->kick_off,
        ]);

        return response()->json($newResult, 200);
    }





    public function undoSaveResult(Request $req)
    {
        // Validate required variables
        $rules = [
            'result_id' => 'required'
        ];

        $validator = Validator::make($req->all(), $rules);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $result_id = $req->input('result_id');
        $result = ResultModel::find($result_id);
        $match = MatchModel::find($result->match_id);



        $homeTeam = $match->home_team;
        $awayTeam = $match->away_team;
        $homeTeam_score = $req->input('homeTeam_score');
        $awayTeam_score = $req->input('awayTeam_score');
        $tour_id = $match->tour_id;


        $team1InStanding = Standings_LeagueModel::where('team_id', $homeTeam)->first();
        $team2InStanding = Standings_LeagueModel::where('team_id', $awayTeam)->first();

        $team1_diff = [
            'team_id' => $homeTeam,
            'tour_id' => $tour_id,
            'played' =>  $team1InStanding->played - 1,
            'points' =>  $team1InStanding->points,
            'won' =>  $team1InStanding->won,
            'lose' =>  $team1InStanding->lose,
            'draw' =>  $team1InStanding->draw,
            'goal_diff' =>  $team1InStanding->goal_diff
        ];

        $team2_diff = [
            'team_id' => $awayTeam,
            'tour_id' => $tour_id,
            'played' =>  $team2InStanding->played - 1,
            'points' =>  $team2InStanding->points,
            'won' =>  $team2InStanding->won,
            'lose' =>  $team2InStanding->lose,
            'draw' =>  $team2InStanding->draw,
            'goal_diff' =>  $team2InStanding->goal_diff
        ];

        (TeamModel::find($homeTeam))->increment('match_played', '-1');
        (TeamModel::find($awayTeam))->increment('match_played', '-1');

        if ($homeTeam_score > $awayTeam_score) {
            $team1_diff['points'] -= 3;
            $team1_diff['won'] -= 1;
            $team2_diff['lose'] -= 1;
        } elseif ($awayTeam_score > $homeTeam_score) {
            $team2_diff['points'] -= 3;
            $team2_diff['won'] -= 1;
            $team1_diff['lose'] -= 1;
        } else {
            $team1_diff['draw'] -= 1;
            $team2_diff['draw'] -= 1;
            $team1_diff['points'] -= 1;
            $team2_diff['points'] -= 1;
        }

        $diff1 = $homeTeam_score - $awayTeam_score;
        $diff2 = $awayTeam_score - $homeTeam_score;
        $team1_diff['goal_diff'] -= $diff1;
        $team2_diff['goal_diff'] -= $diff2;

        Standings_LeagueModel::updateOrCreate(['team_id' => $homeTeam], $team1_diff);
        Standings_LeagueModel::updateOrCreate(['team_id' => $awayTeam], $team2_diff);

        ResultModel::find($result_id)->delete();

        return response()->json(['success' => 'updated successfully'], 200);
    }
}
