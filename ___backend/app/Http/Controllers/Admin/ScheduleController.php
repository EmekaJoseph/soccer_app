<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Validator;

use Illuminate\Http\Request;
use Carbon\Carbon;

use App\Models\TeamModel;
use App\Models\ScheduleModel;
use App\Models\TournamentModel;

class ScheduleController extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;


    public function store(Request $req)
    {
        $rules = [
            'homeTeam' => 'required',
            'awayTeam' => 'required',
            'kick_off' => 'required',
            'venue' => 'required',
            'tour_id' => 'required',
        ];

        $validator = Validator::make($req->all(),  $rules);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $homeTeam = $req->input('homeTeam');
        $awayTeam = $req->input('awayTeam');
        $kick_off = $req->input('kick_off');

        // check teams are valid
        if (sizeof(TeamModel::find([$homeTeam, $awayTeam])) < 2) {
            return response()->json('Team(s) not found', 404);
        }

        if (ScheduleModel::where([
            'home_team' =>  $homeTeam,
            'away_team' =>  $awayTeam,
            'kick_off' => $kick_off
        ])->exists()) {
            return response()->json('Duplicate entries', 203);
        }

        $newSchedule = ScheduleModel::create(
            [
                'tour_id' => $req->input('tour_id'),
                'venue' => $req->input('venue'),
                'kick_off' => $kick_off,
                'home_team' => $homeTeam,
                'away_team' => $awayTeam,
                'match_stage' => $req->input('match_stage'),
                'created' => Carbon::now()
            ]
        );

        return response()->json($newSchedule, 200);
    }



    public function update(Request $req, $schedule_id)
    {
        $rules = [
            'kick_off' => 'required',
            'venue' => 'required'
        ];

        $validator = Validator::make($req->all(),  $rules);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $schedule = ScheduleModel::find($schedule_id);
        $schedule->venue = $req->input('venue');
        $schedule->kick_off = $req->input('kick_off');
        if ($req->input('match_stage'))
            $schedule->match_stage = $req->input('match_stage');
        $schedule->save();

        return response()->json('updated', 200);
    }

    public function index(Request $req)
    {
        $tour_id = $req->input('tour_id');

        $thisTournament = TournamentModel::find($tour_id);
        if (!$thisTournament) {
            return response()->json('invalid tournament', 203);
        }

        $schedules = ScheduleModel::where('tour_id', $req->tour_id)->get();

        if (sizeof($schedules) > 0) {
            foreach ($schedules as $schedule) {
                $schedule->home_team = (TeamModel::find($schedule->home_team))->team_name;
                $schedule->away_team = (TeamModel::find($schedule->away_team))->team_name;
            }
        }

        return response()->json($schedules, 200);
    }




    public function destroy(Request $req, $schedule_id)
    {
        $schedule = ScheduleModel::find($schedule_id);
        $schedule->delete();

        return response()->json('deleted', 200);
    }
}
