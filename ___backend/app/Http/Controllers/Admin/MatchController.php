<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Validator;

use Illuminate\Http\Request;
use Carbon\Carbon;

use App\Models\TeamModel;
use App\Models\MatchModel;
use App\Models\TournamentModel;

class MatchController extends BaseController
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

        if (MatchModel::where([
            'home_team' =>  $homeTeam,
            'away_team' =>  $awayTeam,
            'kick_off' => $kick_off
        ])->exists()) {
            return response()->json('Duplicate entries', 203);
        }

        $newSchedule = MatchModel::create(
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


    public function update(Request $req, $match_id)
    {
        $rules = [
            'kick_off' => 'required',
            'venue' => 'required'
        ];

        $validator = Validator::make($req->all(),  $rules);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $match = MatchModel::find($match_id);
        $match->venue = $req->input('venue');
        $match->kick_off = $req->input('kick_off');
        if ($req->input('match_stage'))
            $match->match_stage = $req->input('match_stage');
        $match->save();

        return response()->json('updated', 200);
    }

    public function index(Request $req)
    {
        $tour_id = $req->input('tour_id');

        $thisTournament = TournamentModel::find($tour_id);
        if (!$thisTournament) {
            return response()->json('invalid tournament', 203);
        }

        $matchs = MatchModel::where('tour_id', $req->tour_id)->get();

        if (sizeof($matchs) > 0) {
            foreach ($matchs as $match) {
                $match->home_team = (TeamModel::find($match->home_team))->team_name;
                $match->away_team = (TeamModel::find($match->away_team))->team_name;
            }
        }

        return response()->json($matchs, 200);
    }




    public function destroy(Request $req, $match_id)
    {
        $match = MatchModel::find($match_id);
        $match->delete();

        return response()->json('deleted', 200);
    }
}
