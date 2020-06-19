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

/**
 * @todo Internacionalize Routes
 */

/**
 * Routes and route groups within this group are subject to all the members of the web middleware group
 *
 * @see App\Http\Kernel::class
 */
Route::group(['middleware' => ['web']], function () {

    Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
    Route::post('login', 'Auth\LoginController@login');
    Route::post('logout', 'Auth\LoginController@logout')->name('logout');
    Route::get('locale/change/{locale}', 'Settings\SettingsController@change_locale')->name('settings.locale.change');

    /**
     * Routes within this group only allow usage for authenticated users
     *
     * @see App\Http\Middleware\Authenticate::class
     *
     * @todo Re-add Auth later
     */
    Route::group(['middleware' => ['web']], function () {

        Route::get('/', 'HomeController@index')->name('home');
        Route::match(['get', 'patch'] ,'profile/edit', 'Settings\UserController@edit_self')->name('settings.users.edit_self');

        /**
         * Routes within this group only allow usage for authenticated users with the proper permissions to use them
         *
         * @see App\Http\Middleware\Allowed::class
         *
         * @todo Re-add allowed later
         */
        Route::group(['middleware' => ['web']], function () {

            // Users
            Route::get(__('routes.users.default'), 'Settings\UserController@index')->name('settings.users.list');
            Route::get(__('routes.users.view'), 'Settings\UserController@view')->name('settings.users.view');
            Route::match(['get', 'post'], __('routes.users.edit'), 'Settings\UserController@edit')->name('settings.users.edit');
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
            Route::match(['get', 'post'], 'roles/edit/{id}', 'Settings\RoleController@edit')->name('settings.roles.edit');
            Route::get('roles/delete/{id}', 'Settings\RoleController@delete')->name('settings.roles.delete');

            // Permissions
            Route::get('permissions', 'Settings\PermissionController@index')->name('settings.permissions.list');
            Route::get('permissions/{id}', 'Settings\PermissionController@view')->name('settings.permissions.view');
            Route::match(['get', 'post'], 'permissions/edit/', 'Settings\PermissionController@edit')->name('settings.permissions.edit');
            Route::get('permissions/delete/{id}', 'Settings\PermissionController@delete')->name('settings.permissions.delete');

            // Failure Types
            Route::get('failure-types', 'Settings\FailureTypeController@index')->name('settings.failure_types.list');
            Route::match(['get', 'post'], 'failure_types/edit/{id}', 'Settings\FailureTypeController@edit')->name('settings.failure_types.edit');
            Route::match(['get', 'post'], 'failure_types/create', 'Settings\FailureTypeController@create')->name('settings.failure_types.create');
            Route::get('failure-types/delete/{id}', 'Settings\FailureTypeController@delete')->name('settings.failure_types.delete');
            Route::get('failure-types/toggle/{id}', 'Settings\FailureTypeController@toggle_state')->name('settings.failure_types.toggle_state');


            // Materials
            Route::get('materials', 'Settings\MaterialController@index')->name('settings.materials.list');
            Route::match(['get', 'post'], 'materials/edit/{id}', 'Settings\MaterialController@edit')->name('settings.materials.edit');
            Route::match(['get', 'post'], 'materials/create/{id?}', 'Settings\MaterialController@create')->name('settings.materials.create');
            Route::get('material/delete/{id}', 'Settings\MaterialController@delete')->name('settings.materials.delete');

            // Daily Reports
            Route::get('daily-reports', 'DailyReportController@index')->name('daily_reports.list');
            Route::get('daily-reports/pending', 'DailyReportController@pending')->name('daily_reports.pending');
            Route::match(['get', 'post'], 'daily-reports/create', 'DailyReportController@create')->name('daily_reports.create');
            Route::get('daily-reports/first-approval', 'DailyReportController@firstApproval')->name('daily_reports.first_approval');
            Route::get('daily-reports/second-approval', 'DailyReportController@secondApproval')->name('daily_reports.second_approval');
            Route::get('daily-reports/approved', 'DailyReportController@approved')->name('daily_reports.approved');
            Route::post('daily-reports/article/get-info', 'DailyReportController@getArticlePrice')->name('daily_reports.article.get_price');
            Route::get('daily-reports/{id}', 'DailyReportController@view')->name('daily_reports.view');
            Route::get('daily-reports/edit/{id}', 'DailyReportController@edit')->name('daily_reports.edit');
            Route::get('daily-reports/regress-status/{id}', 'DailyReportController@regressStatus')->name('daily_reports.prev');
            Route::get('daily-reports/extra-status/{id}', 'DailyReportController@progressExtra')->name('daily_reports.extra');
            Route::get('daily-reports/progress-status/{id}', 'DailyReportController@progressStatus')->name('daily_reports.next');
            Route::get('daily-reports/cancel/{id}', 'DailyReportController@cancel')->name('daily_reports.cancel');
        });
    });
});


// Custom Laravel Filemanager routes,
Route::group(['prefix' => 'laravel-filemanager', 'middleware' => ['web', 'auth']], function () {
    \UniSharp\LaravelFilemanager\Lfm::routes();
});
