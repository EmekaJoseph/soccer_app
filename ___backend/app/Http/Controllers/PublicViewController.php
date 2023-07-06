<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

use App\Models\TournamentModel;
use App\Models\Standings_LeagueModel;
use App\Models\Standings_CupModel;
use App\Models\ResultModel;
use App\Models\TeamModel;
use App\Models\ScheduleModel;
use App\Models\PredictionModel;


class PublicViewController extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    // get tournament details
    public function tourData(Request $req, $tour_id)
    {
        $thisTournament = TournamentModel::find($tour_id);
        return response()->json($thisTournament, 200);
    }


    // fucntion to get standings
    public function standings(Request $req,  $tour_id)
    {
        // check if tournament exists
        $thisTournament = TournamentModel::find($tour_id);
        if (!$thisTournament) {
            return response()->json('invalid tournament', 203);
        }

        $standings = ($thisTournament->tour_type == 'cup') ?
            Standings_CupModel::where('tour_id', $tour_id)->get()
            : Standings_LeagueModel::where('tour_id', $tour_id)->get();
        if (sizeof($standings) > 0) {
            foreach ($standings as $result) {
                $result->team_name = (TeamModel::find($result->team_id))->team_name;
                $result->team_color = (TeamModel::find($result->team_id))->team_color;
                $result->team_badge = (TeamModel::find($result->team_id))->team_badge;
            }
        }

        // if tournament is a cup get from group standings table else get from league standings
        if ($thisTournament->tour_type == 'cup') {

            // group by groups
            $grouped = (collect($standings))->groupBy('group_in');

            // create object according to groups
            $newObj = array();
            foreach ($grouped as $key => $value) {
                // sort by points and goal diff
                $collection = collect($value);
                $sorted = $collection->sortBy([
                    ['points', 'desc'],
                    ['goal_diff', 'desc'],
                    ['team_name', 'asc'],
                ]);
                array_push($newObj, (object)[
                    'group' => $key,
                    'teams' => $sorted->values()->all()
                ]);
            }

            // sort by their groups
            $sortedByGroup = (collect($newObj))->sortBy('group');

            return response()->json($sortedByGroup->values()->all(), 200);
        } else {
            // sort by points and goal diff
            $collection = collect($standings);
            $sorted = $collection->sortBy([
                ['points', 'desc'],
                ['goal_diff', 'desc'],
                ['team_name', 'asc'],
            ]);
            return response()->json($sorted->values()->all(), 200);
        }
    }



    // function to get results
    public function results(Request $req, $tour_id)
    {
        $results = ResultModel::select(
            'result_id',
            'home_team',
            'away_team',
            'home_score',
            'away_score',
            'match_stage',
            'date_played'
        )->where('tour_id', $tour_id)->orderByDesc('date_played')->get();
        if (sizeof($results) > 0) {
            foreach ($results as $result) {
                if ($result->away_score < $result->home_score) {
                    $result->winner =  $result->home_team;
                } else if ($result->away_score > $result->home_score) {
                    $result->winner =  $result->away_team;
                } else {
                    $result->winner =  '';
                }
                $result->home_name = (TeamModel::find($result->home_team))->team_name;
                $result->away_name = (TeamModel::find($result->away_team))->team_name;
            }
        }
        return response()->json($results, 200);
    }


    // function to get schedules
    public function schedules(Request $req, $tour_id)
    {
        $today = Carbon::now()->toDateString();
        $results = ScheduleModel::select(
            'home_team',
            'away_team',
            'match_stage',
            'venue',
            'kick_off'
        )->where('tour_id', $tour_id)->where('kick_off', '>=', $today)->orderBy('kick_off')->get();
        if (sizeof($results) > 0) {
            foreach ($results as $result) {
                $result->home_team = (TeamModel::find($result->home_team))->team_name;
                $result->away_team = (TeamModel::find($result->away_team))->team_name;
            }
        }
        return response()->json($results, 200);
    }

    public function showLiveMatches(Request $req, $tour_id)
    {
        $liveUpdates = DB::table('tbl_live')->where('tour_id', $tour_id)->get();
        if (sizeof($liveUpdates) > 0) {
            foreach ($liveUpdates as $result) {
                $result->home_team = (TeamModel::find($result->home_team))->team_name;
                $result->away_team = (TeamModel::find($result->away_team))->team_name;
            }
        }

        return response()->json($liveUpdates, 200);
    }

    public function save_prediction(Request $req)
    {
        // check if tournament exists
        $thisTournament = TournamentModel::find($req->input('tour_id'));
        if (!$thisTournament) {
            return response()->json('invalid tournament', 203);
        }

        try {
            PredictionModel::create([
                'tour_id' => $req->input('tour_id'),
                'first_place' => $req->input('first_place'),
                'second_place' => $req->input('second_place'),
                'third_place' => $req->input('third_place'),
                'full_name' => $req->input('full_name'),
                'phone_number' => $req->input('phone_number'),
                'email' => $req->input('email'),
                'created_at' => Carbon::now(),
                'device_ip' => $req->ip(),
            ]);
            return response()->json('saved', 200);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json('error', 401);
        }
    }
}
