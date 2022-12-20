<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\isAuthorize;

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
})->name('login');

Route::prefix("ini")->name("ini.")->group(function(){
    Route::post('signin','APIController@signin')->name('signin');
});

Route::prefix("api")->name("api.")->group(function(){
    Route::get('connection','AdminController@connection')->name('connection');
    Route::get('get_connection','AdminController@get_connection')->name('get_connection');
    Route::get('get_station','AdminController@get_station')->name('get_station');
    Route::get('connection_logger','AdminController@connection_logger')->name('connection_logger');
});

Route::prefix("admin")->name("admin.")->middleware('isAuth')->group(function(){
    Route::get('home','AdminController@home')->name('home');
    Route::get('create_station','AdminController@create_station')->name('create_station');
    Route::get('view_station','AdminController@view_station')->name('view_station');
    Route::get('create_master_station','AdminController@create_master_station')->name('create_master_station');
    Route::post('submit_station','AdminController@submit_station')->name('submit_station');
    Route::post('submit_master_station','AdminController@submit_master_station')->name('submit_master_station');
    Route::get('get_master_station','AdminController@get_master_station')->name('get_master_station');
    Route::get('modify_master_station','AdminController@modify_master_station')->name('modify_master_station');
    Route::post('submit_modify_master_station','AdminController@submit_modify_master_station')->name('submit_modify_master_station');
    Route::get('modify_station','AdminController@modify_station')->name('modify_station');
    Route::post('submit_modify_station','AdminController@submit_modify_station')->name('submit_modify_station');
    Route::post('submit_modify_station_tv','AdminController@submit_modify_station_tv')->name('submit_modify_station_tv');
    Route::post('submit_modify_station_shout_cast','AdminController@submit_modify_station_shout_cast')->name('submit_modify_station_shout_cast');
    Route::get('create_user','AdminController@create_user')->name('create_user');
    Route::get('get_station_lov','AdminController@get_station_lov')->name('get_station_lov');
    Route::get('get_master_station_lov','AdminController@get_master_station_lov')->name('get_master_station_lov');
    Route::post('submit_user','AdminController@submit_user')->name('submit_user');
    Route::get('view_user','AdminController@view_user')->name('view_user');
    Route::get('get_user','AdminController@get_user')->name('get_user');
    Route::get('modify_user','AdminController@modify_user')->name('modify_user');
    Route::post('submit_modify_user','AdminController@submit_modify_user')->name('submit_modify_user');
    Route::get('ftp','AdminController@ftp')->name('ftp');
    Route::post('update_ftp','AdminController@update_ftp')->name('update_ftp');
    Route::post('submit_ftp_station','AdminController@submit_ftp_station')->name('submit_ftp_station');
    Route::get('filter_station','AdminController@filter_station')->name('filter_station');
    Route::get('logger','AdminController@logger')->name('logger');
    Route::get('streaming','AdminController@streaming')->name('streaming');
    Route::get('get_streaming_connection','AdminController@get_streaming_connection')->name('get_streaming_connection');
    Route::get('streaming_view_station','AdminController@streaming_view_station')->name('streaming_view_station');
    Route::get('get_streaming_station','AdminController@get_streaming_station')->name('get_streaming_station');
    Route::get('modify_streaming_station','AdminController@modify_streaming_station')->name('modify_streaming_station');
    Route::post('submit_centova_access_account','AdminController@submit_centova_access_account')->name('submit_centova_access_account');
    Route::get('streaming_create_station','AdminController@streaming_create_station')->name('streaming_create_station');
    Route::post('submit_create_streaming_station','AdminController@submit_create_streaming_station')->name('submit_create_streaming_station');
    Route::get('logger_view_station','AdminController@logger_view_station')->name('logger_view_station');
    Route::get('get_logger_station','AdminController@get_logger_station')->name('get_logger_station');
    Route::get('modify_logger_station','AdminController@modify_logger_station')->name('modify_logger_station');
    Route::post('submit_ftp_access_account','AdminController@submit_ftp_access_account')->name('submit_ftp_access_account');
    Route::get('logger_create_station','AdminController@logger_create_station')->name('logger_create_station');
    Route::post('submit_create_logger_station','AdminController@submit_create_logger_station')->name('submit_create_logger_station');
    Route::get('get_logger_connection','AdminController@get_logger_connection')->name('get_logger_connection');
    Route::get('filter','AdminController@filter')->name('filter');
    Route::get('logout','AdminController@logout')->name('logout');
    Route::get('change_password','AdminController@change_password')->name('change_password');
    Route::post('submit_change_password','AdminController@submit_change_password')->name('submit_change_password');
    Route::get('auth_log','AdminController@auth_log')->name('auth_log');
    Route::get('get_auth_log','AdminController@get_auth_log')->name('get_auth_log');
});
