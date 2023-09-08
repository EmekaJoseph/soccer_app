<?php

namespace App\Http\Controllers\Admin;

use App\Models\PredictionModel;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;


use App\Models\TeamModel;
use App\Models\TournamentModel;
use Carbon\Carbon;


class PredictionsController extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;



    public function save_prediction(Request $req)
    {
        // check if tournament exists
        $thisTournament = TournamentModel::find($req->input('tour_id'));
        if (!$thisTournament) {
            return response()->json('invalid tournament', 203);
        }

        try {
            PredictionModel::create([
                'tour_id' => $req->input('tour_id'),
                'first_place' => $req->input('first_place'),
                'second_place' => $req->input('second_place'),
                'third_place' => $req->input('third_place'),
                'full_name' => $req->input('full_name'),
                'phone_number' => $req->input('phone_number'),
                'email' => $req->input('email'),
                'created_at' => Carbon::now(),
                'device_ip' => $req->ip(),
            ]);
            return response()->json('saved', 200);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json('error', 401);
        }
    }

    public function get_predictions(Request $req)
    {
        $predictions = PredictionModel::where('tour_id', $req->input('tour_id'))->orderByDesc('created_at')->get();

        if (sizeof($predictions) > 0) {
            foreach ($predictions as $prediction) {
                $prediction->first_place = (TeamModel::find($prediction->first_place))->team_name;
                $prediction->second_place = (TeamModel::find($prediction->second_place))->team_name;
                $prediction->third_place = (TeamModel::find($prediction->third_place))->team_name;
                $prediction->predicted = Carbon::parse($prediction->created_at)->diffForHumans();
            }
        }
        return response()->json($predictions, 200);
    }

    public function  getWinnersByPrediction(Request $req)
    {

        $predictions = PredictionModel::where('tour_id', $req->input('tour_id'))
            ->where('first_place', $req->first)

            ->when($req->second !== '0', function ($query) use ($req) {
                return $query->where('second_place', $req->second);
            })

            ->when($req->third !== '0', function ($query) use ($req) {
                return $query->where('third_place', $req->third);
            })

            ->orderByDesc('created_at')

            ->get();

        return response()->json($predictions, 200);
    }
}
