<?php

use Illuminate\Support\Facades\Route;
use Modules\Register\App\Http\Controllers\RegisterController;

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

Route::post('/registerss/', [RegisterController::class, 'register'])->name('register');
Route::get('/registeres/', [RegisterController::class, 'show'])->name('registerss.show');

Route::post('/checkpasswords', [RegisterController::class, 'checkPasswords'])->name('passwords.check');

Route::group([
    'middleware' => ['CustomerSupportRole']
], function () {
    Route::get('/admin/customers', [RegisterController::class, 'index'])->name('customers.show');
});


Route::group([
    'middleware' => ['sales']
], function () {
Route::get('/admin/viewcustomers', [RegisterController::class, 'index2'])->name('admin.viewcustomers');
Route::resource('registers', RegisterController::class);
});