<?php

use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return [
        'Laravel' => app()->version(),
        'API' => env('APP_NAME', 'App'),
    ];
})->name('index');


Route::apiResource('autor', 'App\Http\Controllers\AutorController');
Route::apiResource('livro', 'App\Http\Controllers\LivroController');
Route::apiResource('assunto', 'App\Http\Controllers\AssuntoController');

Route::post('/livro/associar-autor', 'App\Http\Controllers\LivroController@connectAutor');
