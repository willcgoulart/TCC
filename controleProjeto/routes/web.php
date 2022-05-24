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

Route::get('/entrar', 'LoginController@index')->name('login');
Route::post('/entrar', 'LoginController@login');

Route::get('/sair', function(){
    \Illuminate\Support\Facades\Auth::logout();
    return redirect('/entrar');
})->name('sair');

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
    Route::delete('deletar', 'EtiquetaController@destroy')->name('form_deletar_etiqueta');
    Route::get('editar/{id}', 'EtiquetaController@editar')->name('form_editar_etiqueta');
    Route::post('editar', 'EtiquetaController@editarSalvar')->name('form_salvar_editar_etiqueta');
});

Route::prefix('/quadro')->group(function () {
    Route::get('', 'QuadroController@index')->name('quadro');
    Route::get('criar', 'QuadroController@create')->name('form_criar_quadro');
    Route::post('criar', 'QuadroController@store')->name('form_criar_quadro');
    Route::delete('deletar', 'QuadroController@destroy')->name('form_deletar_quadro');
    Route::get('editar/{id}', 'QuadroController@editar')->name('form_editar_quadro');
    Route::post('editar', 'QuadroController@editarSalvar')->name('form_salvar_editar_quadro');

    Route::get('lista', 'QuadroController@listaQuadros')->name('lista_quadros');
    Route::get('lista/adm', 'QuadroController@listaQuadrosAdm')->name('lista_quadros_adm');
});

Route::prefix('/tarefa')->group(function () {
    Route::get('pendente', 'TarefaController@listaPendente')->name('tarefa_pendente');
    Route::get('pendente/adm', 'TarefaController@listaPendenteAdm')->name('tarefa_pendente_adm');
    Route::get('atraso', 'TarefaController@listaAtraso')->name('tarefa_atraso');
    Route::get('atraso/adm', 'TarefaController@listaAtrasoAdm')->name('tarefa_atraso_adm');
    Route::get('lista/{id}', 'TarefaController@lista')->name('tarefa_lista');
    Route::get('lista/user/{id}', 'TarefaController@listaUser')->name('tarefa_lista_user');
    Route::post('detalhe', 'TarefaController@buscaDados')->name('tarefa_dados');
    Route::get('demandas', 'TarefaController@listaDemandasUser')->name('tarefa_demanda');
    Route::post('editar', 'TarefaController@editarSalvar')->name('tarefa_salvar'); 
});

Route::prefix('/analise')->group(function () {
    Route::get('', 'AnaliseController@index')->name('analise');
   
});
