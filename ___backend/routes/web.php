<?php

use Illuminate\Support\Facades\Route;

// The Vue SPA is deployed separately; the web routes only show the API status page.
Route::get('/', fn () => view('index'));
