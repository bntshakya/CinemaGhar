<?php

use Illuminate\Support\Facades\Route;
use Modules\Chat\App\Http\Controllers\ChatController;

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

Route::get('/chat', [ChatController::class, 'addchat'])->name('chat.add');
Route::group([
    'middleware' => ['auth:users']
], function () {
        Route::get('/chat/user', [ChatController::class, 'userchat'])->name('chat.userchat');
        Route::get('/chat/user/add', [ChatController::class, 'addchatuser'])->name('chat.users.add');

});