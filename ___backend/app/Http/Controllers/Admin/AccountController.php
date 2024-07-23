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
use App\Models\TeamModel;
use Illuminate\Support\Facades\Auth;
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

        if (SubUserModel::where('email', $req->input('email'))->exists()) {
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

        if (UserModel::where('email', $req->input('email'))->exists()) {
            return response()->json('exists', 203);
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
        $thisUser = Auth::user(); //UserModel::find($req->user()->user_id);
        return response()->json($thisUser->relatedSubUsers, 200);
    }

    public function deleteSubUser(Request $req, $subuser_id)
    {
        SubUserModel::find($subuser_id)->delete();
        return response()->json('deleted', 200);
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

    // reset all tables
    public function resetApp()
    {
        DB::table('tbl_results')->truncate();
        DB::table('tbl_matches')->truncate();
        DB::table('tbl_standings_cup')->truncate();
        DB::table('tbl_standings_league')->truncate();
        DB::table('tbl_live')->truncate();
        DB::table('tbl_teams')->truncate();

        return response()->json('done', 200);
    }
}
