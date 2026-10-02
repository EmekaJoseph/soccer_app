<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LiveMatchModel;
use App\Models\MatchModel;
use App\Models\ResultModel;
use App\Models\TeamModel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $tourIds = $this->account($request)->tournaments()->pluck('tour_id');

        return response()->json([
            'tournaments' => $tourIds->count(),
            'teams' => TeamModel::whereIn('tour_id', $tourIds)->count(),
            'matches' => MatchModel::whereIn('tour_id', $tourIds)->count(),
            'results' => ResultModel::whereIn('tour_id', $tourIds)->count(),
            'live' => LiveMatchModel::whereIn('tour_id', $tourIds)->count(),
        ]);
    }
}
