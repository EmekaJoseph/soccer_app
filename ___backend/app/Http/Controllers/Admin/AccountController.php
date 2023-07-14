<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Validator;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

use App\Models\TournamentModel;
use App\Models\UserModel;
use App\Models\SubUserModel;
use stdClass;

class AccountController extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function userRegister(Request $req)
    {
        // add rule to email type
        $rules = [
            'email' => 'required|email',
            'password' => 'required',
        ];
        $validator = Validator::make($req->all(),  $rules);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        if (UserModel::where('email', $req->input('email'))->exists()) {
            return response()->json('exists', 203);
        }

        $account = new UserModel();
        $account->email = $req->input('email');
        $account->password = Hash::make($req->input('password'));
        $account->save();
        return response()->json('created', 200);
    }



    public function createSubUser(Request $req)
    {
        // add rule to email type
        $rules = [
            'email' => 'required|email',
            'password' => 'required',
        ];
        $validator = Validator::make($req->all(),  $rules);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        if (SubUserModel::where('email', $req->input('email'))->exists()) {
            return response()->json('exists', 203);
        }

        $account = new SubUserModel();
        $account->email = $req->input('email');
        $account->password = Hash::make($req->input('password'));
        $account->user_id = $req->user()->user_id;
        $account->role = 'sub';
        $account->created_at = Carbon::now();
        $account->save();
        return response()->json('created', 200);
    }


    public function subUsersList(Request $req)
    {
        $subUsers =  SubUserModel::where('user_id', $req->user()->user_id)->get();
        return response()->json($subUsers, 200);
    }

    public function deleteSubUser(Request $req, $subuser_id)
    {
        SubUserModel::find($subuser_id)->delete();
        return 'deleted';
    }

    public function userLogin(Request $req)
    {
        $email = $req->input('email');
        $password = $req->input('password');

        // validate passwords?
        $account = UserModel::where('email', $email)->first();
        $subAccount = SubUserModel::where('email', $email)->first();

        if (!$account || !Hash::check($password, $account->password)) {
            if (!$subAccount || !Hash::check($password, $subAccount->password)) {
                return response()->json('invalid login', 203);
            }
        }

        // return logged-in credentials with token
        $data = [
            'id' => $account ? $account->user_id : $subAccount->user_id,
            'email' => $account ? $account->email : $subAccount->email,
            'firstname' => $account ? $account->firstname : $subAccount->firstname,
            'role' => $account ? $account->role : $subAccount->role,
            'token' =>  $account ? $account->createToken('_token')->plainTextToken : $subAccount->createToken('_token')->plainTextToken,
        ];

        return response()->json($data, 200);
    }

    public function userLogout(Request $req)
    {
        try {
            $req->user()->currentAccessToken()->delete();
        } catch (\Throwable $th) {
        }
    }


    // create Tournament
    public function createTournament(Request $req)
    {
        // validate varibles
        $rules = [
            'tour_title' => 'required',
            'tour_type' => 'required | in:cup,league',
        ];

        $validator = Validator::make($req->all(),  $rules);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $tour_title = $req->input('tour_title');
        $tour_type = $req->input('tour_type');

        if (TournamentModel::where(
            ['user_id' => $req->user()->user_id, 'tour_title' => $tour_title]
        )->exists()) {
            return response()->json('exists', 203);
        }

        TournamentModel::create([
            'tour_title' => $tour_title,
            'user_id' => $req->user()->user_id,
            'tour_type' =>   $tour_type,
        ]);

        $user = UserModel::find($req->user()->user_id);

        if ($tour_type == 'league')
            $user->increment('no_of_leagues');
        else
            $user->increment('no_of_cups');

        return response()->json('saved', 200);
    }


    // get Tournaments
    public function getTournaments(Request $req)
    {
        $data = array();

        $tournments = UserModel::find($req->user()->user_id)->relatedTournaments;

        if ($tournments) {
            foreach ($tournments as $list) {
                $newData = new stdClass();
                $newData->title = $list->tour_title;
                $newData->id = $list->tour_id;
                $newData->type = $list->tour_type;
                $newData->created = Carbon::parse($list->created_at)->diffForHumans();
                array_push($data, $newData);
            }
        }

        return response()->json($data, 200);
    }



    // update Tournament
    public function updateTournament(Request $req, $tour_id)
    {
        $tour_title = $req->input('tour_title');

        if (TournamentModel::where('user_id', $req->user()->user_id)
            ->whereNot('tour_id', $tour_id)->where('tour_title', $tour_title)->exists()
        ) {
            return response()->json('exists', 203);
        }

        $tournment = TournamentModel::find($tour_id);

        $tournment->tour_title = $tour_title;

        $tournment->save();

        return response()->json('updated', 200);
    }

    // reset all tables
    public function resetApp()
    {
        DB::table('tbl_results')->truncate();
        DB::table('tbl_schedules')->truncate();
        DB::table('tbl_standings_cup')->truncate();
        DB::table('tbl_standings_league')->truncate();
        DB::table('tbl_live')->truncate();
        DB::table('tbl_teams')->truncate();

        return response()->json('done', 200);
    }
}
