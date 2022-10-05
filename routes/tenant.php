<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;

/*
|--------------------------------------------------------------------------
| Tenant Routes
|--------------------------------------------------------------------------
|
| Here you can register the tenant routes for your application.
| These routes are loaded by the TenantRouteServiceProvider.
|
| Feel free to customize them however you want. Good luck!
|
*/

Route::middleware([
    'web',
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
])->group(function () {
	
    /*
     * Non admin routes
     */
	Auth::routes();
	
	Route::get('/', function () {return view('welcome');});
		
	Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home')->middleware('auth');
	Route::get('/test', [App\Http\Controllers\Tenants\TenantTestController::class, 'index'])->name('test')->middleware('auth');
	Route::get('/test/checklist', [App\Http\Controllers\Tenants\TenantTestController::class, 'checklist'])->name('test.checklist')->middleware('auth');
	Route::get('/info', [App\Http\Controllers\TestController::class, 'info'])->name('info')->middleware('auth');
	Route::get('/test/email', [App\Http\Controllers\TestController::class, 'email'])->name('test.email')->middleware('auth');
	Route::get('/test/menu', [App\Http\Controllers\TestController::class, 'menu'])->name('test.menu')->middleware('auth');
	
	Route::get('/change_password/change_password', [App\Http\Controllers\ChangePasswordController::class, 'change_password'])->name('change_password.change_password');
	Route::patch('/change_password/password', [App\Http\Controllers\ChangePasswordController::class, 'password'])->name('change_password.password');
	
	/*
	 * Warning: routes are parsed in order of declaration and resource matches all the sub urls
	 */
	// Special entry for the fullcalendar monthly view
	Route::get('/calendar_event/fullcalendar', [App\Http\Controllers\Tenants\CalendarEventController::class, 'fullcalendar'])
	->name('calendar_event.fullcalendar')->middleware('auth');
	
	Route::get('/calendar_event/dragged', [App\Http\Controllers\Tenants\CalendarEventController::class, 'dragged'])
	->name('calendar_event.dragged')->middleware('auth');
	
	Route::get('/calendar_event/resized', [App\Http\Controllers\Tenants\CalendarEventController::class, 'resized'])
	->name('calendar_event.resized')->middleware('auth');
	
	Route::get('/calendar_event/json', [App\Http\Controllers\Tenants\CalendarEventController::class, 'json'])
	  ->name('calendar_event.json')->middleware('auth');
	
	// Classic ressource
	Route::resource('calendar_event', App\Http\Controllers\Tenants\CalendarEventController::class)->middleware('auth');
	Route::resource('profile', App\Http\Controllers\Tenants\ProfileController::class)->middleware('auth');
	
	Route::get('/motd/current', [App\Http\Controllers\Tenants\MotdController::class, 'current'])->name('current')->middleware('auth');
	Route::get('/motd/setCookie', [App\Http\Controllers\Tenants\MotdController::class, 'setCookie'])->name('setCookie')->middleware('auth');
	Route::get('/motd/getCookie', [App\Http\Controllers\Tenants\MotdController::class, 'getCookie'])->name('getCookie')->middleware('auth');
	
	/*
	 * admin routes
	 */
	Route::group(['middleware' => ['admin']], function () {
		Route::get('/user/token', [App\Http\Controllers\UserController::class, 'token'])->name('tokens.create')->middleware('auth');
		Route::resource('user', App\Http\Controllers\UserController::class)->middleware('auth');
		
		Route::resource('user_role', App\Http\Controllers\Tenants\UserRoleController::class)->middleware('auth');
		Route::resource('metadata', App\Http\Controllers\Tenants\MetadataController::class)->middleware('auth');
		
		Route::resource('configuration', App\Http\Controllers\Tenants\ConfigurationController::class)->middleware('auth');
		Route::resource('role', App\Http\Controllers\Tenants\RoleController::class)->middleware('auth');
						
		// Backup controller is not a full resource
		Route::get('/backup', [App\Http\Controllers\BackupController::class, 'index'])->name('backup.index')->middleware('auth');
		Route::get('/backup/create', [App\Http\Controllers\BackupController::class, 'create'])->name('backup.create')->middleware('auth');
		Route::get('/backup/{backup}/restore', [App\Http\Controllers\BackupController::class, 'restore'])->name('backup.restore')->middleware('auth');
		Route::delete('/backup/{backup}', [App\Http\Controllers\BackupController::class, 'destroy'])->name('backup.destroy')->middleware('auth');
		Route::get('/backup/{backup}', [App\Http\Controllers\BackupController::class, 'download'])->name('backup.download')->middleware('auth');
		Route::post('/backup', [App\Http\Controllers\BackupController::class, 'upload'])->name('backup.upload')->middleware('auth');

		Route::resource('code_gen_type', App\Http\Controllers\Tenants\CodeGenTypeController::class)->middleware('auth');
		Route::get('/code_gen_type/{id}/show', [App\Http\Controllers\Tenants\CodeGenTypeController::class, 'show'])->name('code_gen_type.show')->middleware('auth');
		Route::post('/code_gen_type/filter', [App\Http\Controllers\Tenants\CodeGenTypeController::class, 'filter'])->name('code_gen_type.filter')->middleware('auth');
		
		Route::get('/code_gen_type/picture/{id}/{field}', [App\Http\Controllers\Tenants\CodeGenTypeController::class, 'picture'])->name('code_gen_type.picture')->middleware('auth');
		Route::get('/code_gen_type/download/{id}/{field}', [App\Http\Controllers\Tenants\CodeGenTypeController::class, 'download'])->name('code_gen_type.file')->middleware('auth');

		Route::get('/code_gen_types_view1/', [App\Http\Controllers\Tenants\CodeGenTypesView1Controller::class, 'index'])->name('code_gen_types_view1')->middleware('auth');

		Route::resource('motd', App\Http\Controllers\Tenants\MotdController::class)
		  ->middleware('auth');
		
		Route::resource('motd_today', App\Http\Controllers\Tenants\MotdTodayController::class)
		  ->middleware('auth');
		
	});			
});

/*
 * APIs
 */
Route::middleware([
			'api',
			InitializeTenancyByDomain::class,
			PreventAccessFromCentralDomains::class,
	])->group(function () {
		
		Route::get('/api/calendar_event/fullcalendar', [App\Http\Controllers\Api\CalendarEventController::class, 'fullcalendar'])
			->name('calendar_event.api.fullcalendar');
		Route::resource('api/calendar_event', App\Http\Controllers\Api\CalendarEventController::class, ['as' => 'api']);
		
		// Route::resource('api/role', App\Http\Controllers\Api\RoleController::class, ['as' => 'api'])->middleware(['auth:sanctum']);
		Route::resource('api/role', App\Http\Controllers\Api\RoleController::class, ['as' => 'api'])
			->middleware(['auth:sanctum', 'ability:check-status,api-access']);

		Route::resource('api/code_gen_type', App\Http\Controllers\Api\CodeGenTypeController::class, ['as' => 'api'])
		; //	->middleware(['auth:sanctum', 'ability:check-status,api-access']);
		
		Route::resource('api/code_gen_types_view1', App\Http\Controllers\Api\CodeGenTypesView1Controller::class, ['as' => 'api'])
			->middleware(['auth:sanctum', 'ability:check-status,api-access']);

		Route::resource('api/profile', App\Http\Controllers\Api\ProfileController::class, ['as' => 'api'])
			->middleware(['auth:sanctum', 'ability:check-status,api-access']);
		
		Route::resource('api/motd', App\Http\Controllers\Api\MotdController::class, ['as' => 'api'])
			->middleware(['auth:sanctum', 'ability:check-status,api-access']);
		
		Route::resource('api/motd_today', App\Http\Controllers\Api\MotdTodayController::class, ['as' => 'api'])
			->middleware(['auth:sanctum', 'ability:check-status,api-access']);
			
	});
	
		
