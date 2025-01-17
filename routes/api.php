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
Route::post('/livro/associar-assunto', 'App\Http\Controllers\LivroController@connectAssunto');
Route::post('/autor/associar-livro', 'App\Http\Controllers\AutorController@connectLivro');
Route::post('/assunto/associar-livro', 'App\Http\Controllers\AssuntoController@connectLivro');


Route::get('/relatorio', 'App\Http\Controllers\RelatorioController@getRelatorio');
