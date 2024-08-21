<?php

use Illuminate\Support\Facades\Route;
use Modules\Admin\App\Http\Controllers\AdminController;
use Modules\Admin\App\Http\Controllers\MovieController;
use App\Http\Controllers\QrcodeController;
use Modules\Login\App\Http\Controllers\LoginController;
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

Route::get('/abc/', [AdminController::class, 'abc']);
Route::group([
    'middleware' => ['sales']
], function () {
Route::get('/admin/revenue', [AdminController::class, 'revenue'])->name('admin.revenue');
Route::get('/admin/revenue/details', [AdminController::class, 'details'])->name('admin.revenue.details');
Route::get('/admin/updateusers', [AdminController::class, 'updateusers'])->name('updateusers');
    Route::get('viewscannedcustomers/', [QrcodeController::class, 'viewscannedcustomers'])->name('admin.viewscannedcustomers');

});

Route::group([
    'middleware' => ['CustomerSupportRole']
], function () {
    Route::get('/selectchatuser', [AdminController::class, 'chat'])->name('chat.selectuser');
    Route::get('/admin/chat', [AdminController::class, 'chat'])->name('admin.chat');
    Route::get('/admin/chat', [AdminController::class, 'chat'])->name('admin.chat');
    Route::get('/admin/movies/list', [AdminController::class, 'movieslist'])->name('admin.movieslist');
    Route::get('/admin/movies/edit', [AdminController::class, 'editMovies'])->name('admin.editMovies');
    Route::get('admin/movies/edit/{id}', [AdminController::class, 'individualedit'])->name('admin.individualEdit');
    Route::get('/add', [MovieController::class, 'add'])->name('movies.add');
    Route::post('/admin/halls/saveData', [AdminController::class, 'saveHallData'])->name('admin.saveHallData');
    Route::get('/admin/halls/edit', [AdminController::class, 'editHallsView'])->name('admin.editHalls');
    Route::post('/admin/halls/delete', [AdminController::class, 'deleteHallRow'])->name('admin.deleteHallRow');
    Route::get('/admin/halls/add', [AdminController::class, 'add'])->name('admin.addHalls');
    Route::get('admin/halls', [AdminController::class, 'halllist'])->name('admin.halllist');
    Route::post('/admin/halls/save', [AdminController::class, 'hallsave'])->name('admin.savehalls');
    Route::get('admin/notification', [AdminController::class, 'notifications'])->name('admin.notifications');
    Route::post('admin/notification/search', [AdminController::class, 'salessearch'])->name('admin.salessearch');
});

Route::group([
    'middleware' => ['MainAdminRole']
], function () {
    Route::get('/admin/panel', [AdminController::class, 'showpanel'])->name('admin.panel');

});

Route::group([
    'middleware' => ['AdminRole']
], function () {
    Route::get('/get-hall-seats-data', [AdminController::class, 'getHallSeatsData']);
    Route::post('/upload', [MovieController::class, 'upload'])->name('movies.upload');
    Route::get('/movies/adddate', [MovieController::class, 'adddate'])->name('movies.adddate');
    Route::get('/movies/addscreening', [MovieController::class, 'addscreening'])->name('movies.addscreening');
    Route::post('/movies/insertnewdate', [MovieController::class, 'insertnewdate'])->name('movies.insertnewdate');
    Route::post('/movies/insertnewscreening', [MovieController::class, 'insertnewscreening'])->name('movies.insertnewscreening');
    Route::post('/admin/edit/', [MovieController::class, 'edit'])->name('movies.edit');
    Route::post('/admin/delete', [MovieController::class, 'delete'])->name('admin.deleteMovie');
});

Route::get('/admin/login', [LoginController::class, 'adminLogin'])->name('admin.login');
Route::post('/admin/login/submit',[LoginController::class,'adminLoginVerification'])->name('admin.Login.Verification');
Route::post('/admin/register/submit',[RegisterController::class,'adminRegisterVerification'])->name('admin.Register.Verification');
Route::get('/admin/register', [LoginController::class, 'adminRegister'])->name('admin.Register');

