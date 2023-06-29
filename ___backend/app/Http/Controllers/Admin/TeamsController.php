<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


use App\Models\TeamModel;
use App\Models\TournamentModel;
use App\Models\Standings_LeagueModel;
use App\Models\Standings_CupModel;
use App\Models\ResultModel;

use Intervention\Image\ImageManagerStatic as Image;

class TeamsController extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    // add a team
    public function store(Request $req)
    {
        // validate varibles
        $rules = [
            'team_name' => 'required',
            'tour_id' => 'required',
        ];

        $validator = Validator::make($req->all(),  $rules);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // get all possible inputs
        $team_name = $req->input('team_name');
        $tour_id = $req->input('tour_id');
        $team_brief = $req->input('team_brief');
        $group_in = $req->input('group_in', null);
        $address = $req->input('address', null);
        $manager = $req->input('manager', null);

        // check if tournament is valid
        $thisTournament = TournamentModel::find($tour_id);
        if (!$tour_id) {
            return response()->json('invalid tournament', 203);
        }

        // check if team exists
        if (TeamModel::where(['tour_id' => $tour_id, 'team_name' => $team_name])->exists()) {
            return response()->json('already exists', 203);
        }

        // add to database
        $newTeam = TeamModel::create([
            'team_name' => $team_name,
            'tour_id' => $tour_id,
            'team_brief' => $team_brief,
            'group_in' => $group_in,
            'address' => $address,
            'manager' => $manager,
        ]);

        if ($thisTournament->tour_type == 'cup') {
            Standings_CupModel::create([
                'team_id' => $newTeam->team_id,
                'tour_id' => $tour_id,
                'group_in' => $group_in
            ]);
        } else {
            Standings_LeagueModel::create([
                'team_id' => $newTeam->team_id,
                'tour_id' => $tour_id,
            ]);
        }

        return response()->json($newTeam->team_id, 200);
    }

    // show all teams in tournament
    public function index(Request $req)
    {
        $tour_id = $req->input('tour_id');
        $thisTournament = TournamentModel::find($tour_id);
        if (!$tour_id) {
            return response()->json('invalid tournament', 203);
        }
        return response()->json($thisTournament->relatedTeams, 200);
    }


    // get team details
    public function show(Request $req, $team_id)
    {
        $data = TeamModel::find($team_id);
        if ($data) {
            $data->tournament = TeamModel::find($team_id)->relatedTournament;
            return response()->json($data, 200);
        }
        return response()->json('team not found', 203);
    }


    // delete a team
    public function destroy(Request $req, $team_id)
    {
        $team = TeamModel::find($team_id);
        $team->delete();

        // delete from standings and results
        Standings_CupModel::where('team_id', $team_id)->delete();
        Standings_LeagueModel::where('team_id', $team_id)->delete();
        ResultModel::where('away_team', $team_id)->orWhere('home_team', $team_id)->delete();
    }


    // update a team
    public function update(Request $req, $team_id)
    {
        // validate varibles
        $rules = ['team_name' => 'required', 'tour_id' => 'required'];

        $validator = Validator::make($req->all(),  $rules);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // get inputs
        $thisName = $req->input('team_name');
        $team_brief = $req->input('team_brief');

        // check if name exists
        if (TeamModel::whereNot('team_id', $team_id)->where([
            'team_name' => $thisName,
            'tour_id' => $req->input('tour_id'),
        ])->exists()) {
            return response()->json('exists', 203);
        }

        $team = TeamModel::find($team_id);

        $team->team_name = $req->input('team_name');
        if ($team_brief) $team->team_brief = $req->input('team_brief');

        $team->save();

        return response()->json('updated', 200);
    }
}
