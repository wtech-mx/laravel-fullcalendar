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

Route::get('/eventos', 'EventosController@index');

Auth::routes();

Route::post('/eventos', 'EventosController@store')->name('eventos.store');

Route::get('/eventos/show', 'EventosController@show')->name('eventos.show');

Route::delete('eventos/destroy/{id}', 'EventosController@destroy')->name('eventos.destroy');

Route::patch('eventos/update/{id}', 'EventosController@update')->name('eventos.update');


