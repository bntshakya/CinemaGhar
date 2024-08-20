<?php

use App\Events\MessageNotification;
// use App\Http\Controllers\AdminController;
// use App\Modules\Admin\AdminController;
use Modules\Admin\App\Http\Controllers\AdminController;
use Modules\Admin\App\Http\Controllers\MovieController;


use App\Http\Controllers\AlertController;
use Modules\Chat\App\Http\Controllers\ChatController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MailController;
use App\Http\Controllers\QrcodeController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\StripeController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\WebhookController; // Add this line to import the WebhookController class
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WebHookStripeController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::get('/malle', function () {
    return view('malle');
});

Route::get('/', [MovieController::class, 'show'])->name('movie.show');
Route::get('/tickets', [TicketController::class, 'show'])->name('tickets.show');

Route::get('qrcode/', [QrcodeController::class, 'generateqr'])->name('qr.generate');
Route::get('/moviecustomersscanned/', [QrcodeController::class, 'scannedcustomers'])->name('qr.moviecustomerscanned');
;
Route::get('/users', [UsersController::class, 'index'])->name('users.index');
// ------------------------------------------------------------------------------------------------------------------------------------------------------------------------
//adminroutes
Route::post('/tickets/trigger', [StripeController::class, 'handleStripeWebhook'])->name('stripe.trigger');
Route::post('/abc', [StripeController::class, 'xyz']);

// Route::get('send-mail', [MailController::class, 'index']);
Route::get('/send-email', [MailController::class, 'sendEmail']);
Route::get('/send-mail', [MailController::class, 'index']);

route::get('/send', [TestController::class, 'index']);

Route::get('/event', function () {
    event(new MessageNotification('this is our 1st broadcast message'));
});

Route::get('/listen', function () {
    return view('listen');
});

Route::get('/trigger-alert', [AlertController::class, 'triggerAlert']);

Route::view('/trigger', 'trigger');
Route::view('/listen', 'listen');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/stripe-key',[StripeController::class,'getKey'])->name('stripe.key');

Route::get('/client-secret',[TicketController::class,'test2'])->name('stripe.clientSecret');
Route::get('/count',[UserController::class,'count'])->name('count');
Route::group([
    'middleware' => ['auth:users']
], function () {
Route::post('/create-confirm-intent',[UserController::class,'submitDetails'])->name('stripe.submitDetails');
});