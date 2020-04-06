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
    return view('home.index');
});

// Route::get('/dashboard', 'DashboardController@index')->name('dashboard.index');
// Route::get('/', 'HomeController@index')->name('home.index');
Route::get('/home', 'HomeController@index')->name('home.index');
//Dokter
Route::match(['get', 'post'], '/dokter',            'DokterController@index')->name('dokter.index');
Route::match(['get', 'post'], '/dokter/search',     'DokterController@search')->name('dokter.search');
Route::get('/dokter',                               'DokterController@list')->name('dokter.list');
Route::post('/dokter/add',                          'DokterController@store')->name('dokter.store');
Route::put('/dokter/update/{id}',                   'DokterController@update')->name('dokter.update');
Route::delete('/dokter/delete/{id}',                'DokterController@delete')->name('dokter.delete');
Route::get('/dokter/detail/{id}',                   'DokterController@show')->name('dokter.show');
Route::get('/dokter/get/{id}',                   'DokterController@get_dokter')->name('dokter.get_dokter');
Route::get('/dokter/_search',                       'DokterController@search_autocomplete')->name('dokter.search_autocomplete');
Route::post('/dokter/by/rumkit',                    'DokterController@search_by_rumkit')->name('dokter.search_by_rumkit');
Route::get('/dokter/by/skill/{id}',                 'DokterController@search_by_skill')->name('dokter.search_by_skill');
Route::get('/dokter/by/spesialis/{id}',             'DokterController@search_by_spesialis')->name('dokter.search_by_spesialis');
Route::post('/dokter/by/spesialis_lokasi',          'DokterController@search_by_spesialis_lokasi')->name('dokter.search_by_spesialis_lokasi');
Route::get('/dokter/spesialis/list',                'DokterController@spesialis_list')->name('dokter.spesialis_list');
Route::get('/dokter/keahlian/list',                 'DokterController@keahlian_list')->name('dokter.keahlian_list');
Route::get('/dokter/rumkit/list',                   'DokterController@rumkit_list')->name('dokter.rumkit_list');
Route::match(['get', 'post'], 'dokter/spesialis',   'DokterController@spesialis')->name('dokter.spesialis');
Route::match(['get', 'post'], 'dokter/keahlian',    'DokterController@keahlian')->name('dokter.keahlian');
Route::match(['get', 'post'], 'dokter/rumkit',      'DokterController@rumkit')->name('dokter.rumkit');
