<?php

use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return [
        'Laravel' => app()->version(),
        'API' => env('APP_NAME', 'App'),
    ];
})->name('index');


Route::apiResource('autor', 'App\Http\Controllers\AutorController');
