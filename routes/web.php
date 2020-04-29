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

        // Users
        Route::get('users', 'Settings\UserController@index')->name('settings.users.list');
        Route::get('users/{id}', 'Settings\UserController@view')->name('settings.users.view');
        Route::match(['get', 'post'], 'users/edit/{id}', 'Settings\UserController@edit')->name('settings.users.edit');
        Route::get('users/toggle/{id}', 'Settings\UserController@toggle_state')->name('settings.users.toggle_state');
        Route::get('users/delete/{id}', 'Settings\UserController@delete')->name('settings.users.delete');
        Route::get('users/add', 'Settings\UserController@create')->name('settings.users.create');

        // Work Types
        Route::get('work_types', 'Settings\UserController@index')->name('settings.work_types.list');
        Route::get('work_types/{id}', 'Settings\UserController@view')->name('settings.work_types.view');
        Route::match(['get', 'post'], 'work_types/edit/{id}', 'Settings\UserController@edit')->name('settings.work_types.edit');
        Route::get('work_types/toggle/{id}', 'Settings\UserController@toggle_state')->name('settings.work_types.toggle_state');
        Route::get('work_types/delete/{id}', 'Settings\UserController@delete')->name('settings.work_types.delete');

        // Delegations
        Route::get('delegations', 'Settings\UserController@index')->name('settings.delegations.list');
        Route::get('delegations/{id}', 'Settings\UserController@view')->name('settings.delegations.view');
        Route::match(['get', 'post'], 'delegations/edit/{id}', 'Settings\UserController@edit')->name('settings.delegations.edit');
        Route::get('work_types/toggle/{id}', 'Settings\UserController@toggle_state')->name('settings.delegations.toggle_state');
        Route::get('work_types/delete/{id}', 'Settings\UserController@delete')->name('settings.delegations.delete');

        // Agents
        Route::get('agents', 'Settings\AgentController@index')->name('settings.agents.list');
        Route::get('agents/{id}', 'Settings\AgentController@view')->name('settings.agents.view');
        Route::match(['get', 'post'], 'agents/edit/{id}', 'Settings\AgentController@edit')->name('settings.agents.edit');
        Route::get('agents/toggle/{id}', 'Settings\AgentController@toggle_state')->name('settings.agents.toggle_state');
        Route::get('agents/delete/{id}', 'Settings\AgentController@delete')->name('settings.agents.delete');

        // Roles
        Route::get('roles', 'Settings\RoleController@index')->name('settings.roles.list');
        Route::get('roles/{id}', 'Settings\RoleController@view')->name('settings.roles.view');
        Route::match(['get', 'post'], 'agents/edit/{id}', 'Settings\RoleController@edit')->name('settings.roles.edit');
        Route::get('roles/delete/{id}', 'Settings\RoleController@delete')->name('settings.roles.delete');

        // Roles
        Route::get('permissions', 'Settings\PermissionController@index')->name('settings.permissions.list');
        Route::get('permissions/{id}', 'Settings\PermissionController@view')->name('settings.permissions.view');
        Route::match(['get', 'post'], 'agents/edit/{id}', 'Settings\PermissionController@edit')->name('settings.permissions.edit');
        Route::get('permissions/delete/{id}', 'Settings\PermissionController@delete')->name('settings.permissions.delete');
        Route::get('permissions/toggle/{id}', 'Settings\PermissionController@toggle_state')->name('settings.permissions.toggle_state');
    });
});
