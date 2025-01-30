<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AccountController;
use App\Http\Controllers\Admin\DashbordController;
use App\Http\Controllers\Admin\TeamsController;
use App\Http\Controllers\Admin\MatchController;
use App\Http\Controllers\Admin\LiveMatchesController;
use App\Http\Controllers\Admin\PlayersContoller;
use App\Http\Controllers\Admin\ResultsController;
use App\Http\Controllers\Admin\TournamentController;

Route::controller(AccountController::class)->group(function () {
    Route::get('userLogout',  'userLogout');
    Route::post('createSubUser',  'createSubUser');
    Route::get('deleteSubUser/{subuser_id}',  'deleteSubUser');
    Route::get('subUsersList',  'subUsersList');
});

Route::get('dashboard',  [DashbordController::class, 'index']);

Route::get('getTournaments',  [TournamentController::class, 'getTournaments']);
Route::post('createTournament',  [TournamentController::class, 'createTournament']);
Route::post('updateTournament',  [TournamentController::class, 'update']);
Route::get('deleteTournament/{tour_id}',  [TournamentController::class, 'deleteTournament']);

Route::resource('team', TeamsController::class)->except(['index']);;

Route::resource('match', MatchController::class);

Route::post('cup/save_result', [ResultsController::class, 'saveCupResult']);

Route::post('cup/undo_save_result/{result_id}', [ResultsController::class, 'undoSaveCupResult']);

Route::post('league/save_result', [ResultsController::class, 'saveLeagueResult']);

Route::post('league/undo_save_result/{result_id}', [ResultsController::class, 'undoSaveLeagueResult']);

Route::controller(LiveMatchesController::class)->group(function () {
    Route::post('startLiveMatch',  'startLiveMatch');
    Route::post('updateLiveMatch/{live_id}',  'updateLiveMatch');
    Route::get('endLiveMatch/{live_id}',  'endLiveMatch');
    Route::get('getLiveMatchesByUser/{tour_id}',  'getLiveMatchesByUser');
    Route::get('getLiveMatchesForAdmin/{tour_id}',  'getLiveMatchesForAdmin');
});


Route::post('player', [PlayersContoller::class, 'store']);
Route::patch('player/{player_id}', [PlayersContoller::class, 'update']);
Route::delete('player/{player_id}', [PlayersContoller::class, 'destroy']);
Route::post('player/image', [PlayersContoller::class, 'updateImage']);


//  ########################################################################### //
