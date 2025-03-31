<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ImportController;

Route::get('/welcome', function () {
    return view('welcome');
});

Route::get('/importAds',[ImportController::class, 'importAds']);

Route::get('/importAds/{id}', [ImportController::class, 'getImportJobStatus']);