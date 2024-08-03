<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return [
        'Laravel' => app()->version(),
        'API' => env('APP_NAME', 'App'),
    ];
});

Route::get('/test', [\App\Http\Controllers\TesteController::class, 'index']);

Route::get('/user', function (Request $request) {
    return 'user route';
});
