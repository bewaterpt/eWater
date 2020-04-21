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

// Route::get('/', function () {
//     return view('welcome');
// });

// Auth::routes();

Route::group(['middleware' => ['web']], function () {
    Route::get('/', 'HomeController@index')->name('home');
    Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
    Route::post('login', 'Auth\LoginController@login');
    Route::post('logout', 'Auth\LoginController@logout')->name('logout');
    Route::match(['get', 'patch'] ,'profile/edit', 'Settings\UserController@edit_self')->name('settings.users.edit_self');
    Route::get('locale/change/{locale}', 'Settings\SettingsController@change_locale')->name('settings.locale.change');

    Route::group(['middleware' => ['allowed']], function () {
        Route::get('users', 'Settings\UserController@index')->name('settings.users');
        Route::get('users/{id}', 'Settings\UserController@view')->name('settings.users.view');
        Route::match(['get', 'post'], 'users/edit/{id}', 'Settings\UserController@edit')->name('settings.users.edit');
        Route::get('users/toggle/{id}', 'Settings\UserController@toggle_state')->name('settings.users.toggle_state');
        Route::get('users/delete/{id}', 'Settings\UserController@delete')->name('settings.users.delete');
        Route::get('work_types', 'Settings\UserController@index')->name('settings.users.list');
        Route::get('users/{id}', 'Settings\UserController@view')->name('settings.users.view');
        Route::match(['get', 'post'], 'users/edit/{id}', 'Settings\UserController@edit')->name('settings.users.edit');
        Route::get('users/toggle/{id}', 'Settings\UserController@toggle_state')->name('settings.users.toggle_state');
        Route::get('users/delete/{id}', 'Settings\UserController@delete')->name('settings.users.delete');

    });
});
