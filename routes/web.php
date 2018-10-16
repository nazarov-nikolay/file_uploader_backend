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
    return view('home');
});

Route::get('/uploaded/{email_hash}/{file_hash}', 'FileController@download');

Auth::routes();

Route::get('/admin', 'AdminController@index')->name('admin_file_list');
Route::get('/admin/{file_id}', 'AdminController@info')->name('admin_file_info');
Route::post('/admin/{file_id}', 'AdminController@edit')->name('admin_file_edit');
