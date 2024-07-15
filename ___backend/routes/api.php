<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AccountController;
use App\Http\Controllers\Admin\PlayersContoller;
use App\Http\Controllers\Admin\TeamsController;
use App\Http\Controllers\Admin\PredictionsController;

use App\Http\Controllers\PublicViewController;


//  ######################## UNPROTECTED ########################## //
Route::controller(AccountController::class)->group(function () {
    Route::post('userRegister',  'userRegister');
    Route::post('userLogin',  'userLogin');

    /**WARNING REMOVE!!!!!**/ Route::get('resetApp',  'resetApp');
});


Route::prefix('view')->group(function () {
    Route::get('players/{tour_id}', [PlayersContoller::class, 'players']);
    Route::get('tour_data/{tour_id}', [PublicViewController::class, 'tourData']);
    Route::get('standings/{tour_id}',   [PublicViewController::class, 'standings']);
    Route::get('results/{tour_id}',   [PublicViewController::class, 'results']);
    Route::get('matches/{tour_id}',   [PublicViewController::class, 'matches']);
    Route::get('live/{tour_id}',   [PublicViewController::class, 'showLiveMatches']);
    Route::get('infomationCenter/{tour_id}',   [PublicViewController::class, 'infomationCenter']);
});

Route::controller(PredictionsController::class)->group(function () {
    Route::post('save_prediction',  'save_prediction');
    Route::get('get_predictions',  'get_predictions');
    Route::post('getWinnersByPrediction',  'getWinnersByPrediction');
});


Route::post('sendFeedBack', [PublicViewController::class, 'sendFeedBack']);
Route::get('getFeedbacks', [PublicViewController::class, 'getFeedbacks']);

Route::resource('team', TeamsController::class)->only(['index']);

// players
Route::get('getFeedbacks', [PublicViewController::class, 'getFeedbacks']);


//  ######################## PROTECTED WITH SANCTUM ########################## //

Route::group(['middleware' => ['auth:sanctum']], function () {
    require __DIR__ . '/adminApi.php';
});

//  ########################################################################### //
