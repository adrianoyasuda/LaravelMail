<?php

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
    return view('main');
});

Route::post('/enviar', 'MailController@enviar');
Route::post('/concluir', 'MailController@concluir');

Route::get('/socios', 'SocioController@listar');
Route::post('/cadastrar', 'SocioController@cadastrar');
Route::post('/enviar', 'SocioController@enviar');