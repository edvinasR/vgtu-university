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
    return view('welcome');
});

Auth::routes();
Route::get('/home', 'HomeController@index')->name('home');
Route::middleware('admin')->group(function () {
	Route::resource('administratrius/instruktorius', 'Administratorius\\InstruktoriusController');
	Route::resource('administratrius/ket_grupe', 'Administratorius\\KET_grupeController');
	Route::resource('administratrius/mokinys', 'Administratorius\\MokinysController');
	Route::resource('administratrius/paskaita', 'Administratorius\\PaskaitaController');
	Route::resource('administratrius/mokinio-busena', 'Administratorius\\MokinioBusenaController');
	Route::resource('administratrius/ivertinimas', 'Administratorius\\IvertinimasController');
	Route::resource('administratrius/zinute', 'Administratorius\\ZinuteController');
	Route::resource('administratrius/grupiu-paskaitos', 'Administratorius\\GrupiuPaskaitosController');
	Route::resource('administratrius/naudotojas', 'Administratorius\\UsersController');
});

Route::middleware('ket')->group(function () {
	Route::resource('ket_instruktorius/ket_grupe', 'Ket_instruktorius\\GrupesController');
	Route::resource('ket_instruktorius/mokinys', 'Ket_instruktorius\\MokiniaiController');
	Route::resource('ket_instruktorius/ivertinimai', 'Ket_instruktorius\\IvertinimaiController');
	Route::resource('ket_instruktorius/paskaitos', 'Ket_instruktorius\\PaskaitosController');
	Route::get('ket_instruktorius/ket_paskaitos/{strat}/{end}','Ket_instruktorius\\PaskaitosController@gautiInstruktoriausPaskaitas');
	Route::post('ket_instruktorius/ivertinimai/grupes','Ket_instruktorius\\IvertinimaiController@getGrupes');
	Route::get('ket_instruktorius/ivertinimai/mokiniai/{id}','Ket_instruktorius\\IvertinimaiController@getMokiniai');
	Route::get('ket_instruktorius/ivertinimai/paskaita/{id}','Ket_instruktorius\\IvertinimaiController@getIvertinimai');
		
	});

Route::middleware('instruktorius')->group(function () {
		
	Route::resource('instruktorius/mokinys', 'Instruktorius\\MokiniaiController');
	Route::resource('instruktorius/ivertinimai', 'Instruktorius\\IvertinimaiController');
	Route::resource('instruktorius/paskaitos', 'Instruktorius\\PaskaitosController');
	Route::resource('instruktorius/zinute', 'Instruktorius\\ZinutesController');
	Route::get('instruktorius/paskaitos/{strat}/{end}','Instruktorius\\PaskaitosController@gautiInstruktoriausPaskaitas');
	Route::get('instruktorius/mokinio_ivertinimai/{strat}/{end}','Instruktorius\\IvertinimaiController@gautiInstruktoriausPaskaitas');
	
	
});

Route::middleware('mokinys')->group(function () {
	Route::resource('mokinys/paskaitos', 'Mokinys\\PaskaitosController');
	Route::get('mokinys/paskaitos/{strat}/{end}', 'Mokinys\\PaskaitosController@gautiMokinioPaskaitas');
	Route::resource('mokinys/zinute', 'Mokinys\\ZinutesController');
	Route::resource('mokinys/ivertinimas', 'Mokinys\\IvertinimasController');
	Route::get('mokinys/egzaminai','Mokinys\\IvertinimasController@gautiEgzaminus');
	
});


Route::get('export/grupes','ExportController@exportGroups');
Route::get('export/grupes/{id}','ExportController@exportGroup');
Route::get('export/tvarkarastis/{instruktoriausId}/{start}/{end}','ExportController@exportuotiTvarkarasti');
Route::get('export/praktines_paskaitos/{instruktoriausId}/{start}/{end}','ExportController@exportuotipraktiniuPaskaituTvarkarasti');
Route::get('export/mokinys/tvarkarastis/{mokinioId}/{start}/{end}','ExportController@exportuotiMokinioTvarkarasti');

