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

Route::get('/', [App\Http\Controllers\WelcomeController::class, 'index'])->name('welcome');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/change_password/change_password', [App\Http\Controllers\ChangePasswordController::class, 'change_password'])->name('change_password.change_password');
Route::patch('/change_password/password', [App\Http\Controllers\ChangePasswordController::class, 'password'])->name('change_password.password');

Route::get('/repas', [App\Http\Controllers\RepasController::class, 'index'])->name('repas.csv');
Route::post('/repas', [App\Http\Controllers\RepasController::class, 'csv'])->name('repas.csv');

// admin routes
Route::group(['middleware' => ['admin']], function () {
	Route::resource('user', App\Http\Controllers\UserController::class)->middleware('auth');
	Route::resource('tenants', App\Http\Controllers\TenantController::class)->middleware('auth');

	// Backup controller is not a full resource
	Route::get('/backup', [App\Http\Controllers\BackupController::class, 'index'])->name('backup.index')->middleware('auth');
	Route::get('/backup/create', [App\Http\Controllers\BackupController::class, 'create'])->name('backup.create')->middleware('auth');
	Route::get('/backup/{backup}/restore', [App\Http\Controllers\BackupController::class, 'restore'])->name('backup.restore')->middleware('auth');
	Route::delete('/backup/{backup}', [App\Http\Controllers\BackupController::class, 'destroy'])->name('backup.destroy')->middleware('auth');
	Route::get('/backup/{backup}', [App\Http\Controllers\BackupController::class, 'download'])->name('backup.download')->middleware('auth');
	Route::post('/backup', [App\Http\Controllers\BackupController::class, 'upload'])->name('backup.upload')->middleware('auth');

	Route::get('/test', [App\Http\Controllers\TestController::class, 'index'])->name('test')->middleware('auth');
	Route::get('/info', [App\Http\Controllers\TestController::class, 'info'])->name('info')->middleware('auth');
	Route::get('/test/email', [App\Http\Controllers\TestController::class, 'email'])->name('test.email')->middleware('auth');
});
