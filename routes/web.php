<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ImportController;

Route::get('/welcome', function () {
    return view('welcome');
});

Route::get('/importAds',[ImportController::class, 'importAds']);