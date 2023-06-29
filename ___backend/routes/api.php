<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AccountController;
use App\Http\Controllers\Admin\TeamsController;
use App\Http\Controllers\Admin\ScheduleController;
use App\Http\Controllers\Admin\Results_CupController;
use App\Http\Controllers\Admin\Results_LeagueController;

use App\Http\Controllers\PublicViewController;



//  ######################## UNPROTECTED ########################## //
Route::controller(AccountController::class)->group(function () {
    Route::post('userCreate',  'userCreate');
    Route::post('userLogin',  'userLogin');
    /**WARNING REMOVE!!!!!**/ Route::get('resetApp',  'resetApp');
});


Route::controller(PublicViewController::class)->prefix('view')->group(function () {
    Route::get('tour_data/{tour_id}',  'tourData');
    Route::get('standings/{tour_id}',  'standings');
    Route::get('results/{tour_id}',  'results');
    Route::get('schedules/{tour_id}',  'schedules');
});

//  ############################################################## //


//  ######################## PROTECTED WITH SANCTUM ########################## //

Route::group(['middleware' => ['auth:sanctum']], function () {

    Route::controller(AccountController::class)->group(function () {
        Route::post('createTournament',  'createTournament');
        Route::get('getTournaments',  'getTournaments');
        Route::post('updateTournament/{tour_id}',  'updateTournament');
        Route::get('userLogout',  'userLogout');
    });

    Route::resource('team', TeamsController::class);

    Route::resource('schedule', ScheduleController::class);

    Route::post('cup/save_result', [Results_CupController::class, 'saveResult']);

    Route::post('cup/undo_save_result/{result_id}', [Results_CupController::class, 'undoSaveResult']);

    Route::post('league/save_result', [Results_LeagueController::class, 'saveResult']);

    Route::post('league/undo_save_result/{result_id}', [Results_LeagueController::class, 'undoSaveResult']);
});

//  ########################################################################### //