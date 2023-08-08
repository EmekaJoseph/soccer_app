<?php

namespace App\Http\Controllers\Admin;

use App\Events\endMatch;
use App\Events\liveScore;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Validator;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LiveUpdateController extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function startLiveMatch(Request $req)
    {
        // validate required varibles
        $rules = [
            'home_team' => 'required',
            'away_team' => 'required',
            'match_stage' => 'required',
            'tour_id' => 'required',
        ];
        $validator = Validator::make($req->all(),  $rules);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        DB::table('tbl_live')->insert([
            'home_team' => $req->input('home_team'),
            'away_team' => $req->input('away_team'),
            'tour_id' => $req->input('tour_id'),
            'match_stage' => $req->input('match_stage'),

        ]);

        return response()->json('started', 200);
    }

    public function updateLiveMatch(Request $req, $live_id)
    {
        // validate required varibles
        $rules = [
            'home_team_score' => 'required',
            'away_team_score' => 'required',
            'curr_time' => 'required',
        ];

        $validator = Validator::make($req->all(),  $rules);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $dataToUpdate = [
            'home_team_score' => $req->input('home_team_score'),
            'away_team_score' => $req->input('away_team_score'),
            'curr_time' => $req->input('curr_time'),
        ];

        DB::table('tbl_live')->where('live_id', $live_id)->update($dataToUpdate);

        event(new liveScore($live_id, $dataToUpdate));

        return response()->json('updated', 200);
    }

    public function endLiveMatch(Request $req, $live_id)
    {
        DB::table('tbl_live')->where('live_id', $live_id)->delete();
        event(new endMatch($live_id));
        return response()->json('ended', 200);
    }
}
