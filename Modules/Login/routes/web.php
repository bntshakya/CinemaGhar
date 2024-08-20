<?php

use Illuminate\Support\Facades\Route;
use Modules\Login\App\Http\Controllers\LoginController;

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
Route::get('/logins/', [LoginController::class, 'show'])->name('logins.show');
Route::post('/logins/', [LoginController::class, 'login'])->name('logins');
Route::get('/log-out/', [LoginController::class, 'logout'])->name('register.logout');

