<?php

use Illuminate\Http\Request;

Route::post('login', 'UserController@login'); //do login
Route::post('login/siswa', 'SiswaController@login'); //do login
Route::post('register', 'UserController@register'); //do register

Route::group(['middleware' => ['jwt.verify']], function () {
    Route::get('login/check', "UserController@LoginCheck"); //cek token
    Route::post('logout', "UserController@logout"); //cek token

    // Petugas 
    Route::get('petugas', "UserController@index"); //read semua petugas
    Route::get('petugas/{limit}/{offset}', "UserController@getAll"); //read dengan limit petugas
    Route::post('petugas', "UserController@store"); //create petugas
    Route::put('petugas/{id}', "UserController@update"); //update petugas
    Route::delete('petugas/{id}', "UserController@delete"); //delete petugas

    // Kelas
    Route::get('kelas', "KelasController@index"); //read kelas
	Route::get('kelas/{limit}/{offset}', "KelasController@getAll"); //read kelas
	Route::post('kelas', 'KelasController@store'); //create kelas
	Route::put('kelas/{id}', "KelasController@update"); //update kelas
	Route::delete('kelas/{id}', "KelasController@delete"); //delete kelas

    // Spp
    Route::get('spp', "SppController@index"); //read spp
    Route::get('spp/siswa/{id_siswa}', "SppController@getSppSiswa"); //read spp
	Route::get('spp/{limit}/{offset}', "SppController@getAll"); //read spp
	Route::get('sppku/{limit}/{offset}', "SppController@getAllKu"); //read spp
	Route::post('spp', 'SppController@store'); //create spp
	Route::put('spp/{id}', "SppController@update"); //update spp
	Route::delete('spp/{id}', "SppController@delete"); //delete spp

    //Siswa
    Route::get('siswa', "SiswaController@index"); //read siswa
	Route::get('siswa/{limit}/{offset}', "SiswaController@getAll"); //read siswa
	Route::post('siswa', 'SiswaController@store'); //create siswa
	Route::put('siswa/{id}', "SiswaController@update"); //update siswa
	Route::delete('siswa/{id}', "SiswaController@delete"); //delete siswa

    //Pembayaran
    Route::get('pembayaran', "PembayaranController@index"); //read pembayaran
	Route::get('pembayaran/{limit}/{offset}', "PembayaranController@getAll"); //read pembayaran
	Route::get('pembayaranku/{limit}/{offset}', "PembayaranController@getAllku"); //read pembayaran
	Route::post('pembayaran', 'PembayaranController@store'); //create pembayaran
	Route::put('pembayaran/{id}', "PembayaranController@update"); //update pembayaran
	Route::delete('pembayaran/{id}', "PembayaranController@delete"); //delete pembayaran

});