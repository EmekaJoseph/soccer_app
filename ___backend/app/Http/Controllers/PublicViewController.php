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
use App\Models\FeedbackModel;

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
            'kick_off',
        )->where('tour_id', $tour_id)->where('kick_off', '>=', $today)->orderBy('kick_off')->get();
        if (sizeof($results) > 0) {
            foreach ($results as $result) {
                $result->home_team = (TeamModel::find($result->home_team));
                $result->away_team = (TeamModel::find($result->away_team));
            }
        }
        return response()->json($results, 200);
    }

    public function showLiveMatches(Request $req, $tour_id)
    {
        $liveUpdates = DB::table('tbl_live')->where('tour_id', $tour_id)->get();
        if (sizeof($liveUpdates) > 0) {
            foreach ($liveUpdates as $result) {
                $result->home_team = (TeamModel::find($result->home_team));
                $result->away_team = (TeamModel::find($result->away_team));
            }
        }

        return response()->json($liveUpdates, 200);
    }

    public function sendFeedBack(Request $req)
    {
        // check if tournament exists
        $thisTournament = TournamentModel::find($req->input('tour_id'));
        if (!$thisTournament) {
            return response()->json('invalid tournament', 203);
        }

        try {
            FeedbackModel::create([
                'tour_id' => $req->input('tour_id'),
                'name' => $req->input('name'),
                'feedbackText' => $req->input('feedbackText'),
                'created_at' => Carbon::now(),
                'device_ip' => $req->ip(),
            ]);
            return response()->json('saved', 200);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function getFeedbacks(Request $req)
    {
        $data =  FeedbackModel::all();
        return response()->json($data, 200);
    }


    public function infomationCenter($tour_id)
    {
        $teams = TeamModel::where('tour_id', $tour_id)->get();
        $results = ResultModel::where('tour_id', $tour_id)->get();

        $data = array();

        if ($results) {

            // initialize wins, draws and losts
            foreach ($teams as $team) {
                $team->played = 0;
                $team->won = 0;
                $team->draw = 0;
                $team->lost = 0;
                $team->scored = 0;
                $team->conceded = 0;
                $team->goal_diff = 0;
            }


            // calculate wins, draws and losts
            foreach ($teams as $team) {
                foreach ($results as $result) {
                    if ($result->home_team == $team->team_id) {
                        if ($result->home_score > $result->away_score) {
                            $team->won += 1;
                        } else if ($result->home_score < $result->away_score) {
                            $team->lost += 1;
                        } else {
                            $team->draw += 1;
                        }
                        $team->scored += $result->home_score;
                        $team->conceded += $result->away_score;
                        $team->played += 1;
                    } else if ($result->away_team == $team->team_id) {
                        if ($result->away_score > $result->home_score) {
                            $team->won += 1;
                        } else if ($result->away_score < $result->home_score) {
                            $team->lost += 1;
                        } else {
                            $team->draw += 1;
                        }
                        $team->scored += $result->away_score;
                        $team->conceded += $result->home_score;
                        $team->played += 1;
                    }
                }
                $team->goal_diff = $team->scored  - $team->conceded;
            }


            // calculate performance rating
            foreach ($teams as $team) {
                if ($team->played !== 0) {
                    $rating = (($team->won * 3) + $team->draw) + ($team->goal_diff / 10);
                    // $rating = ((($team->won * 3) + $team->draw) / ($team->played * 3)) * 100;
                    $team->rating = ceil($rating);
                }

                array_push($data, $team);
            }

            // $collection = collect($data);
            // $data = $collection->sortBy([
            //     ['played', 'desc'],
            //     ['rating', 'rating'],
            // ]);

            usort($data, function ($a, $b) {
                return $b->rating - $a->rating;
            });


            // usort($objects, function($a, $b) {
            //     // Compare by property1
            //     if ($a->property1 == $b->property1) {
            //         // If property1 is the same, compare by property2
            //         return $a->property2 <=> $b->property2;
            //     }
            //     return $a->property1 <=> $b->property1;
            // });
        }


        return response()->json($data, 200);
    }
}
