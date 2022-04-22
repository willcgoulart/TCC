<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/teste', function () {
    return view('teste');
});

Route::get('/entrar', 'LoginController@index')->name('login');
Route::post('/entrar', 'LoginController@login');

Route::prefix('/user')->group(function () {
    Route::get('', 'UserController@index')->name('user');
    Route::get('cadastrar', 'UserController@create')->name('form_cadastra_user');
    Route::post('cadastrar', 'UserController@store')->name('form_cadastra_user');
    Route::delete('deletar', 'UserController@destroy')->name('form_deletar_user');

    Route::get('editar/{id}', 'UserController@editar')->name('form_editar_user');
    Route::post('editar', 'UserController@editarSalvar')->name('form_salvar_editar_user');
});

Route::prefix('/dashboard')->group(function () {
    Route::get('', 'DashboardController@index')->name('dashboard');
});

Route::prefix('/etiqueta')->group(function () {
    Route::get('', 'EtiquetaController@index')->name('etiqueta');
    Route::get('criar', 'EtiquetaController@create')->name('form_criar_etiqueta');
    Route::post('criar', 'EtiquetaController@store')->name('form_criar_etiqueta');
});

Route::prefix('/quadro')->group(function () {
    Route::get('', 'QuadroController@index')->name('quadro');
    Route::get('criar', 'QuadroController@create')->name('form_criar_quadro');
    Route::post('criar', 'QuadroController@store')->name('form_criar_quadro');
    Route::delete('deletar', 'QuadroController@destroy')->name('form_deletar_quadro');
    Route::get('editar/{id}', 'QuadroController@editar')->name('form_editar_quadro');
    Route::post('editar', 'QuadroController@editarSalvar')->name('form_salvar_editar_quadro');
});