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
    Route::get('check-call-record-update-state', 'Yealink\CallController@checkCallUpdateState')->name('calls.check_update_state');

    /**
     * Routes within this group only allow usage for authenticated users
     *
     * @see App\Http\Middleware\Authenticate::class
     */
    Route::group(['middleware' => ['auth']], function () {

        Route::get('/', 'HomeController@index')->name('home');
        Route::match(['get', 'patch'], 'profile/edit', 'Settings\UserController@edit_self')->name('settings.users.edit_self');
        Route::post('daily-reports/process-status/get-comment', 'DailyReportController@getProcessStatusComment')->name('daily_reports.process_status.get_comment');
        Route::post('daily-reports/article/get-info', 'DailyReportController@getArticlePrice')->name('daily_reports.article.get_price');
        Route::post('teams/get-users', 'Settings\TeamController@getTeamUsers')->name('settings.teams.get_users');

        Route::impersonate();

        /**
         * Routes within this group only allow usage for authenticated users with the proper permissions to use them
         *
         * @see App\Http\Middleware\Allowed::class
         */
        Route::group(['middleware' => ['allowed']], function () {

            // Users
            Route::get(__('routes.users.default'), 'Settings\UserController@index')->name('settings.users.list');
            Route::get(__('routes.users.view'), 'Settings\UserController@view')->name('settings.users.view');
            Route::get(__('routes.users.edit'), 'Settings\UserController@edit')->name('settings.users.edit');
            Route::post(__('routes.users.update'), 'Settings\UserController@update')->name('settings.users.update');
            Route::get('users/toggle/{id}', 'Settings\UserController@toggleState')->name('settings.users.toggle_state');
            Route::get('users/delete/{id}', 'Settings\UserController@delete')->name('settings.users.delete');
            Route::get('users/add', 'Settings\UserController@create')->name('settings.users.create');

            // Work Types
            Route::get('work_types', 'Settings\UserController@index')->name('settings.work_types.list');
            Route::get('work_types/{id}', 'Settings\UserController@view')->name('settings.work_types.view');
            Route::match(['get', 'post'], 'work_types/edit/{id}', 'Settings\UserController@edit')->name('settings.work_types.edit');
            Route::get('work_types/toggle/{id}', 'Settings\UserController@toggle_state')->name('settings.work_types.toggle_state');
            Route::get('work_types/delete/{id}', 'Settings\UserController@delete')->name('settings.work_types.delete');

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
            // Route::get('permissions/{id}', 'Settings\PermissionController@view')->name('settings.permissions.view');
            Route::post('permissions/update', 'Settings\PermissionController@update')->name('settings.permissions.edit');
            // Route::get('permissions/delete/{id}', 'Settings\PermissionController@delete')->name('settings.permissions.delete');

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
            // Route::get('material/delete/{id}', 'Settings\MaterialController@delete')->name('settings.materials.delete');

            // Process Statuses
            Route::get('statuses', 'Settings\StatusController@index')->name('settings.statuses.list');
            Route::post('statuses/update/{id}', 'Settings\StatusController@update')->name('settings.statuses.update');
            Route::match(['get', 'post'], 'statuses/edit/{id}', 'Settings\StatusController@edit')->name('settings.statuses.edit');
            Route::match(['get', 'post'], 'statuses/create', 'Settings\StatusController@create')->name('settings.statuses.create');
            Route::get('statuses/delete/{id}', 'Settings\StatusController@delete')->name('settings.statuses.delete');
            Route::get('statuses/toggle/{id}', 'Settings\StatusController@toggleState')->name('settings.statuses.toggle_state');

            // Daily Reports
            Route::get('daily-reports', 'DailyReportController@index')->name('daily_reports.list');
            Route::get('daily-reports/pending', 'DailyReportController@pending')->name('daily_reports.pending');
            Route::get('daily-reports/create', 'DailyReportController@create')->name('daily_reports.create');
            Route::post('daily-reports/store', 'DailyReportController@store')->name('daily_reports.store');
            // Route::get('daily-reports/first-approval', 'DailyReportController@firstApproval')->name('daily_reports.first_approval');
            // Route::get('daily-reports/second-approval', 'DailyReportController@secondApproval')->name('daily_reports.second_approval');
            // Route::get('daily-reports/approved', 'DailyReportController@approved')->name('daily_reports.approved');
            Route::get('daily-reports/{id}', 'DailyReportController@view')->name('daily_reports.view');
            Route::get('daily-reports/edit/{id}', 'DailyReportController@edit')->name('daily_reports.edit');
            Route::post('daily-reports/update/{id}', 'DailyReportController@update')->name('daily_reports.update');
            Route::post('daily-reports/regress-status/{id}', 'DailyReportController@regressStatus')->name('daily_reports.prev');
            Route::post('daily-reports/extra-status/{id}', 'DailyReportController@progressExtra')->name('daily_reports.extra');
            Route::post('daily-reports/progress-status/{id}', 'DailyReportController@progressStatus')->name('daily_reports.next');
            Route::get('daily-reports/cancel/{id}', 'DailyReportController@cancel')->name('daily_reports.cancel');
            Route::get('daily-reports/restore/{id}', 'DailyReportController@restore')->name('daily_reports.restore');

            // Interruptions
            Route::get('interruptions', 'InterruptionController@index')->name('interruptions.list');
            Route::get('interruptions/scheduled', 'InterruptionController@scheduled')->name('interruptions.list_scheduled');
            Route::get('interruptions/unscheduled', 'InterruptionController@unscheduled')->name('interruptions.list_unscheduled');
            Route::get('interruptions/create', 'InterruptionController@create')->name('interruptions.create');
            Route::get('interruptions/view/{id}', 'InterruptionController@view')->name('interruptions.view');
            Route::post('interruptions/store', 'InterruptionController@store')->name('interruptions.store');
            Route::get('interruptions/edit/{id}', 'InterruptionController@edit')->name('interruptions.edit');
            Route::post('interruptions/update/{id}', 'InterruptionController@update')->name('interruptions.update');
            Route::get('interruptions/delete/{id}', 'InterruptionController@delete')->name('interruptions.delete');
            // Route::get('interruptions/restore/{id}', 'InterruptionController@restore')->name('interruptions.restore');

            // Teams
            Route::get('teams', 'Settings\TeamController@index')->name('settings.teams.list');
            Route::get('teams/create', 'Settings\TeamController@create')->name('settings.teams.create');
            Route::post('teams/store', 'Settings\TeamController@store')->name('settings.teams.store');
            Route::get('teams/edit/{id}', 'Settings\TeamController@edit')->name('settings.teams.edit');
            Route::post('teams/update/{id}', 'Settings\TeamController@update')->name('settings.teams.update');
            Route::get('teams/delete/{id}', 'Settings\TeamController@delete')->name('settings.teams.delete');

            // Works
            Route::get('works', 'WorkController@index')->name('works.list');
            Route::get('works/create', 'WorkController@create')->name('works.create');
            Route::post('works/store', 'WorkController@store')->name('works.store');
            Route::get('works/edit/{id}', 'WorkController@edit')->name('works.edit');
            Route::post('works/update/{id}', 'WorkController@update')->name('works.update');
            Route::post('works/work-exists', 'WorkController@workExists')->name('works.exists');
            // Route::get('works/delete/{id}', 'WorkController@delete')->name('settings.teams.delete');

            // CDR and Recordings
            Route::get('calls', 'Yealink\CallController@index')->name('calls.list');
            Route::get('calls/pbx', 'Yealink\CallController@pbxList')->name('calls.pbx.list');
            Route::get('calls/refetch', 'Yealink\CallController@refetch')->name('calls.refetch');
            Route::get('calls/pbx/create', 'Yealink\CallController@pbxCreate')->name('calls.pbx.create');
            Route::post('calls/pbx/store', 'Yealink\CallController@pbxStore')->name('calls.pbx.store');
            Route::get('calls/pbx/edit/{id}', 'Yealink\CallController@pbxEdit')->name('calls.pbx.edit');
            Route::post('calls/pbx/update/{id}', 'Yealink\CallController@pbxUpdate')->name('calls.pbx.update');
            Route::get('calls/export/{filetype?}', 'Yealink\CallController@export')->name('calls.export');
            Route::match(['get', 'post'], 'calls/get_monthly_wait_time_info', 'Yealink\CallController@getMonthlyWaitTimeInfo')->name('calls.charts.get_monthly_wait_time_info');
            Route::match(['get', 'post'], 'calls/get_monthly_call_number_info', 'Yealink\CallController@getMonthlyCallNumberInfo')->name('calls.charts.get_monthly_call_number_info');

            // Forms
            Route::get('forms', 'FormController@index')->name('settings.forms.list');
            Route::get('forms/create', 'FormController@create')->name('settings.forms.create');
            Route::post('forms/store', 'FormController@store')->name('settings.forms.store');
            Route::get('forms/edit', 'FormController@edit')->name('settings.forms.edit');
            Route::post('forms/update', 'FormController@update')->name('settings.forms.update');
            Route::match(['post'], 'forms/view', 'FormController@view')->name('settings.forms.view');

            // Delegations
            Route::get('delegations', 'Settings\DelegationController@index')->name('settings.delegations.list');
            Route::get('delegations/create', 'Settings\DelegationController@create')->name('settings.delegations.create');
            Route::post('delegations/store', 'Settings\DelegationController@store')->name('settings.delegations.store');
            Route::get('delegations/edit/{id}', 'Settings\DelegationController@edit')->name('settings.delegations.edit');
            Route::post('delegations/update/{id}', 'Settings\DelegationController@update')->name('settings.delegations.update');
            Route::get('delegations/delete/{id}', 'Settings\DelegationController@delete')->name('settings.delegations.delete');

            // Interruption Motives
            Route::get('interruptions/motives', 'Settings\MotivesController@index')->name('interruptions.motives.list');
            Route::get('interruptions/motives/create', 'Settings\MotivesController@create')->name('interruptions.motives.create');
            Route::post('interruptions/motives/store', 'Settings\MotivesController@store')->name('interruptions.motives.store');
            Route::get('interruptions/motives/edit/{id}', 'Settings\MotivesController@edit')->name('interruptions.motives.edit');
            Route::post('interruptions/motives/update/{id}', 'Settings\MotivesController@update')->name('interruptions.motives.update');
            Route::get('interruptions/motives/delete/{id}', 'Settings\MotivesController@delete')->name('interruptions.motives.delete');
            Route::get('interruptions/motives/restore/{id}', 'Settings\MotivesController@restore')->name('interruptions.motives.restore');

            Route::match(['get', 'post'], 'test', 'TestController@index')->name('tests.test');
        });
    });
});
