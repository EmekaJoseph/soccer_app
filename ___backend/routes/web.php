<?php

use Illuminate\Support\Facades\Route;
use App\Events\liveScore;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/broadcast', function () {
//     // broadcast(new liveScore());
//     return "Event has been sent!";
// });


Route::get('/', function () {
    return view('index');
});

Route::fallback(function () {
    return view('index');
});
