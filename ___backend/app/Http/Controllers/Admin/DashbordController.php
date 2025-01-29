<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

use Illuminate\Http\Request;

use App\Models\UserModel;
use App\Models\TeamModel;
use Illuminate\Support\Facades\Auth;

class DashbordController extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function index(Request $req)
    {
        $tournaments = UserModel::find(Auth::id())->relatedTournaments ?? collect();

        $teamIds = $tournaments->pluck('tour_id');

        $teams = TeamModel::whereIn('tour_id', $teamIds)->get();

        $data = [
            'tournaments' => $tournaments->count(),
            'teams' => $teams->count()
        ];

        return response()->json($data, 200);
    }
}
