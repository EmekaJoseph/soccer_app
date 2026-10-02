<?php

use App\Http\Controllers\Admin\AccountController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\FeedbackController;
use App\Http\Controllers\Admin\LiveMatchesController;
use App\Http\Controllers\Admin\MatchController;
use App\Http\Controllers\Admin\PlayersController;
use App\Http\Controllers\Admin\PredictionsController;
use App\Http\Controllers\Admin\ResultsController;
use App\Http\Controllers\Admin\SubUserController;
use App\Http\Controllers\Admin\TeamsController;
use App\Http\Controllers\Admin\TournamentController;
use App\Http\Controllers\PublicViewController;
use Illuminate\Support\Facades\Route;

//  ######################## PUBLIC ########################## //

Route::controller(AccountController::class)->middleware('throttle:10,1')->group(function () {
    Route::post('register', 'register');
    Route::post('login', 'login');
    Route::post('forgot-password', 'forgotPassword');
    Route::post('reset-password', 'resetPassword');
});

Route::prefix('view/tournaments/{tournament}')->controller(PublicViewController::class)->group(function () {
    Route::get('/', 'tournament');
    Route::get('standings', 'standings');
    Route::get('results', 'results');
    Route::get('matches', 'matches');
    Route::get('live', 'live');
    Route::get('teams', 'teams');
    Route::get('players', 'players');

    Route::middleware('throttle:10,1')->group(function () {
        Route::post('predictions', 'storePrediction');
        Route::post('feedback', 'storeFeedback');
    });
});

//  ######################## SIGNED IN (admins and sub-users) ########################## //

Route::middleware('auth:sanctum')->group(function () {
    Route::controller(AccountController::class)->group(function () {
        Route::post('logout', 'logout');
        Route::get('me', 'me');
        Route::put('me', 'updateProfile');
        Route::put('me/password', 'changePassword');
    });

    Route::get('dashboard', DashboardController::class);

    Route::get('tournaments', [TournamentController::class, 'index']);
    Route::get('tournaments/{tournament}/teams', [TeamsController::class, 'index']);
    Route::get('tournaments/{tournament}/matches', [MatchController::class, 'index']);
    Route::get('tournaments/{tournament}/results', [ResultsController::class, 'index']);
    Route::get('tournaments/{tournament}/live', [LiveMatchesController::class, 'index']);

    Route::apiResource('matches', MatchController::class)->only(['store', 'update', 'destroy']);

    Route::post('results', [ResultsController::class, 'store']);
    Route::delete('results/{result}', [ResultsController::class, 'destroy']);

    Route::controller(LiveMatchesController::class)->group(function () {
        Route::post('live', 'store');
        Route::put('live/{live}', 'update');
        Route::post('live/{live}/end', 'end');
    });

    //  ######################## OWNER (admin) ONLY ########################## //

    Route::middleware('admin')->group(function () {
        Route::apiResource('tournaments', TournamentController::class)->only(['store', 'update', 'destroy']);
        Route::get('tournaments/{tournament}/live/all', [LiveMatchesController::class, 'all']);
        Route::get('tournaments/{tournament}/predictions', [PredictionsController::class, 'index']);
        Route::get('tournaments/{tournament}/predictions/winners', [PredictionsController::class, 'winners']);
        Route::get('tournaments/{tournament}/players', [PlayersController::class, 'index']);
        Route::get('feedback', [FeedbackController::class, 'index']);

        Route::apiResource('teams', TeamsController::class)->only(['store', 'update', 'destroy']);
        Route::apiResource('players', PlayersController::class)->only(['store', 'update', 'destroy']);
        Route::apiResource('sub-users', SubUserController::class)->only(['index', 'store', 'destroy'])
            ->parameters(['sub-users' => 'subUser']);
    });
});
