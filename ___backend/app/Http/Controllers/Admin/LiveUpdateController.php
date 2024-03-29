<?php

namespace App\Http\Controllers\Admin;

use App\Events\endMatch;
use App\Events\liveScore;
use App\Events\startMatch;
use App\Models\TeamModel;
use App\Models\SubUserModel;
use App\Models\UserModel;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Validator;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        $currentUser = ($req->user()->role == 'admin') ? $req->user()->user_id : $req->user()->subuser_id;

        DB::table('tbl_live')->insert([
            'home_team' => $req->input('home_team'),
            'away_team' => $req->input('away_team'),
            'tour_id' => $req->input('tour_id'),
            'match_stage' => $req->input('match_stage'),
            'creator' => $currentUser
        ]);

        try {
            event(new startMatch());
        } catch (\Throwable $th) {
            //throw $th;
        }

        return response()->json('started', 200);
    }


    public function getLiveMatchesForAdmin($tour_id)
    {
        $me = Auth::user();
        $liveUpdates = DB::table('tbl_live')
            ->where('tour_id', $tour_id)
            ->get();
        if (sizeof($liveUpdates) > 0) {
            foreach ($liveUpdates as $live) {
                $live->home_team = (TeamModel::find($live->home_team))->team_name;
                $live->away_team = (TeamModel::find($live->away_team))->team_name;

                $creator = SubUserModel::find($live->creator);

                if (!$creator) {
                    $creator = UserModel::find($live->creator);
                }

                $live->isMe = ($creator->email === $me->email) ? 'You' : null;

                $live->creator =  $creator;
            }
        }

        return response()->json($liveUpdates, 200);
    }


    public function getLiveMatchesByUser(Request $req, $tour_id)
    {
        $currentUser = ($req->user()->role == 'admin') ? $req->user()->user_id : $req->user()->subuser_id;
        $liveUpdates = DB::table('tbl_live')
            ->where('tour_id', $tour_id)
            ->where('creator', $currentUser)
            ->get();
        if (sizeof($liveUpdates) > 0) {
            foreach ($liveUpdates as $result) {
                $result->home_team = (TeamModel::find($result->home_team))->team_name;
                $result->away_team = (TeamModel::find($result->away_team))->team_name;
            }
        }

        return response()->json($liveUpdates, 200);
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

        try {
            event(new liveScore($live_id, $dataToUpdate));
        } catch (\Throwable $th) {
            //throw $th;
        }

        return response()->json('updated', 200);
    }

    public function endLiveMatch(Request $req, $live_id)
    {
        DB::table('tbl_live')->where('live_id', $live_id)->delete();
        try {
            event(new endMatch($live_id));
        } catch (\Throwable $th) {
            //throw $th;
        }

        return response()->json('ended', 200);
    }
}
