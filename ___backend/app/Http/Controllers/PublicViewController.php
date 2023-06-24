<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

use Illuminate\Http\Request;

use App\Models\TournamentModel;
use App\Models\Standings_LeagueModel;
use App\Models\Standings_CupModel;
use App\Models\ResultModel;
use App\Models\TeamModel;
use App\Models\ScheduleModel;


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
                ]);
                array_push($newObj, (object)[
                    'group' => $key,
                    'teams' => $sorted->values()->all()
                ]);
            }

            $sortedByGroup = (collect($newObj))->sortBy('group');

            return response()->json($sortedByGroup->values()->all(), 200);
        } else {
            // sort by points and goal diff
            $collection = collect($standings);
            $sorted = $collection->sortBy([
                ['points', 'desc'],
                ['goal_diff', 'desc'],
            ]);
            return response()->json($sorted->values()->all(), 200);
        }
    }



    // function to get results
    public function results(Request $req, $tour_id)
    {
        $results = ResultModel::select(
            'home_team',
            'away_team',
            'home_score',
            'away_score',
            'match_stage',
            'date_played'
        )->where('tour_id', $tour_id)->orderBy('date_played')->get();
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
        $results = ScheduleModel::select(
            'home_team',
            'away_team',
            'match_stage',
            'venue',
            'kick_off'
        )->where('tour_id', $tour_id)->orderBy('kick_off')->get();
        if (sizeof($results) > 0) {
            foreach ($results as $result) {
                $result->home_team = (TeamModel::find($result->home_team))->team_name;
                $result->away_team = (TeamModel::find($result->away_team))->team_name;
            }
        }
        return response()->json($results, 200);
    }
}
