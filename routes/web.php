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

Route::get('index', 'PageController@getIndex')->name('trangchu');
Route::get('loai-san-pham/{type}', 'PageController@getLoaiSp')->name('loaisanpham');
Route::get('chi-tiet-san-pham/{id}', 'PageController@getChitiet')->name('chitietsanpham');
Route::get('lien-he', 'PageController@getLienHe')->name('lienhe');
Route::get('gioi-thieu', 'PageController@getGioiThieu')->name('gioithieu');
Route::get('add-to-cart/{id}', 'PageController@getAddtoCart')->name('themgiohang');
Route::get('del-cart/{id}', 'PageController@getDelItemCart')->name('xoagiohang');

Route::get('dat-hang','PageController@getCheckout')->name('dathang');
Route::post('dat-hang','PageController@postCheckout')->name('dathang');

Route::get('dang-nhap', 'PageController@getLogin')->name('login');
Route::post('dang-nhap', 'PageController@postLogin')->name('login');
Route::get('dang-ki', 'PageController@getSignin')->name('signin');
Route::post('dang-ki', 'PageController@postSignin')->name('signin');
Route::get('dang-xuat', 'PageController@getLogout')->name('logout');
Route::get('search', 'PageController@getSearch')->name('search');

