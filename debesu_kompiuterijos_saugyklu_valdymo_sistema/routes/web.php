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

Route::middleware(['auth'])->group(function () {
    //Rinkmenų valdymas
        //-------------------------------------------------------------------------------
        Route::group(['prefix' => 'file'], function(){
            Route::post('{parentId}/{service?}', 'FileController@post');
            Route::get('{fileId}', 'FileController@get');
            Route::get('{fileId}/content', 'FileController@download');
 
            Route::put('{fileId}', 'FileController@rename');
            Route::put('move/{directoryId}','FileController@move');
            Route::post('create/folder/{directoryId}','FileController@createFolder');
            Route::delete('', 'FileController@delete');
            Route::get('{fileId}/service/{service}/id/{id}/thumb', 'FileController@loadIamgeThumbnail');


        });
        // Rinkmenų valdymo erdvė
        //-------------------------------------------------------------------------------
        Route::get('/rinkmenos/{parent}/{storageService?}', 'RinkmenuErvesPuslapioKontroleris@get');
        Route::get('/folders/{file}','RinkmenuErvesPuslapioKontroleris@getFolderHierarchy');
        Route::get('/view/change','RinkmenuErvesPuslapioKontroleris@changeViewFormat');
        Route::get('/saugyklos','CloudServicesLandingPageController@get');
        Route::post('/files/content/archived/{service?}', 'FileController@downloadUsingQueue');
        //----------------------------------------------------------------------------
        
        // Nustatymai
        //-------------------------------------------------------------------------------
        Route::get('/nustatymai', 'SettingsController@index');
        Route::post('/saugykla', 'SettingsController@createStorageService');
        Route::delete('/saugykla/{id}', 'SettingsController@deleteStorageService');
        Route::get('/saugykla/{id}/about', 'SettingsController@getAbout');
        Route::put('/saugykla/{id}/rename', 'SettingsController@renameStorageService');
        Route::get('/saugykla/{id}/aktyvuoti', 'SettingsController@activateStorageService');
        Route::get('/test/{id}', 'SettingsController@test');
        Route::get('/update_storage_info', 'SettingsController@updateStorageInfo');

        //-------------------Atsisiuntimai-----------------------------------------------
        Route::get('/atsisiuntimai', 'AtisiuntimaiController@index');
        Route::get('/atsisiuntimai/{id}', 'AtisiuntimaiController@download');

        //-------------------------Saugyklų statistika-----------------------------   
        Route::get('/busena', 'BusenuController@index');
        Route::get('/busena/extension/{service?}', 'BusenuController@getUserStorageServiceExtensionStatistics');
        Route::get('/busena/general/{service?}', 'BusenuController@getUserStorageServiceGeneralStatistics');
        Route::get('/busena/free_storage', 'BusenuController@getFreeStorageData');
        Route::get('/busena/used_storage', 'BusenuController@getUsedStorageData');
    
        //----------------Oauth---------------------------------------------------------
        Route::get('oauth/google','OAuthController@googleRediriect');
        Route::get('oauth/onedrive','OAuthController@oneDriveRedirect');
        Route::get('oauth/dropbox','OAuthController@dropBoxRedriect');
        //-------------------------------------------------------------------------------

});

