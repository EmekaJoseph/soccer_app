<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AccountController;
use App\Http\Controllers\Admin\TeamsController;
use App\Http\Controllers\Admin\ScheduleController;
use App\Http\Controllers\Admin\Results_CupController;
use App\Http\Controllers\Admin\Results_LeagueController;
use App\Http\Controllers\Admin\LiveUpdateController;
use App\Http\Controllers\Admin\PredictionsController;

use App\Http\Controllers\PublicViewController;


//  ######################## UNPROTECTED ########################## //
Route::controller(AccountController::class)->group(function () {
    Route::post('userRegister',  'userRegister');
    Route::post('userLogin',  'userLogin');

    /**WARNING REMOVE!!!!!**/ Route::get('resetApp',  'resetApp');
});


Route::controller(PublicViewController::class)->prefix('view')->group(function () {
    Route::get('tour_data/{tour_id}',  'tourData');
    Route::get('standings/{tour_id}',  'standings');
    Route::get('results/{tour_id}',  'results');
    Route::get('schedules/{tour_id}',  'schedules');
    Route::get('live/{tour_id}',  'showLiveMatches');
    Route::get('infomationCenter/{tour_id}',  'infomationCenter');
});

Route::controller(PredictionsController::class)->group(function () {
    Route::post('save_prediction',  'save_prediction');
    Route::get('get_predictions',  'get_predictions');
    Route::post('getWinnersByPrediction',  'getWinnersByPrediction');
});


Route::post('sendFeedBack', [PublicViewController::class, 'sendFeedBack']);
Route::get('getFeedbacks', [PublicViewController::class, 'getFeedbacks']);

Route::resource('team', TeamsController::class)->only(['index']);

//  ############################################################## //


//  ######################## PROTECTED WITH SANCTUM ########################## //

Route::group(['middleware' => ['auth:sanctum']], function () {

    Route::controller(AccountController::class)->group(function () {
        Route::post('createTournament',  'createTournament');
        Route::get('getTournaments',  'getTournaments');
        Route::post('updateTournament/{tour_id}',  'updateTournament');
        Route::get('userLogout',  'userLogout');
        Route::post('createSubUser',  'createSubUser');
        Route::get('deleteSubUser/{subuser_id}',  'deleteSubUser');
        Route::get('subUsersList',  'subUsersList');
    });

    Route::resource('team', TeamsController::class)->except(['index']);;

    Route::resource('schedule', ScheduleController::class);

    Route::post('cup/save_result', [Results_CupController::class, 'saveResult']);

    Route::post('cup/undo_save_result/{result_id}', [Results_CupController::class, 'undoSaveResult']);

    Route::post('league/save_result', [Results_LeagueController::class, 'saveResult']);

    Route::post('league/undo_save_result/{result_id}', [Results_LeagueController::class, 'undoSaveResult']);

    Route::controller(LiveUpdateController::class)->group(function () {
        Route::post('startLiveMatch',  'startLiveMatch');
        Route::post('updateLiveMatch/{live_id}',  'updateLiveMatch');
        Route::get('endLiveMatch/{live_id}',  'endLiveMatch');
        Route::get('getLiveMatchesByUser/{tour_id}',  'getLiveMatchesByUser');
        Route::get('getLiveMatchesForAdmin/{tour_id}',  'getLiveMatchesForAdmin');
    });
});

//  ########################################################################### //
