<?php

use App\Http\Controllers\StripeController;
use Illuminate\Support\Facades\Route;
use Modules\User\App\Http\Controllers\UserController;
use Modules\Admin\App\Http\Controllers\MovieController;
use App\Http\Controllers\TicketController;

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

Route::get('/movies/details', [MovieController::class, 'details'])->name('movies.details');
Route::get('movies/search/', [MovieController::class, 'search'])->name('movies.search');
Route::get('movies/location', [MovieController::class, 'location'])->name('movies.location');
Route::group([
    'middleware' => ['auth:users']
], function () {
    Route::post('/tickets/modal/pay',[TicketController::class,'PaymentModalPay'])->name('User.PaymentModal.Pay');
    Route::get('movies/time', [MovieController::class, 'time'])->name('movies.time');
    Route::post('/tickets/buy', [TicketController::class, 'buy'])->name('tickets.buy');
    Route::get('/tickets/view/', [TicketController::class, 'view'])->name('tickets.view');
    Route::get('/tickets/purchase', [TicketController::class, 'purchase'])->name('tickets.purchase');
    Route::post('/tickets/purchase/embed', [TicketController::class, 'test'])->name('tickets.purchase.embed');
    Route::post('/tickets/purchase/api',[TicketController::class,'apiPurchase'])->name('tickets.purchase.api');
    Route::get(
    '/card/view',[UserController::class,'viewcards']
    )->name('card.view');
    Route::post(
        '/card/add',
        [UserController::class, 'addCards']
    )->name('card.add');

    Route::get('/stripe-key', function () {
        return response()->json(['publicKey' => env('STRIPE_PUBLIC_KEY')]);
    });
    Route::get(
        '/customer/cards',[UserController::class,'viewPaymentMethods']
    )->name('User.viewPaymentMethods');
    Route::post('/cards/set',[UserController::class,'setCard'])->name('User.setCard');
    Route::get('/user/subscription/view',[UserController::class,'viewSubscriptions'])->name('User.Subscriptions');
    Route::post('/user/subscription/select',[UserController::class,'selectSubscriptionMethod'])->name('User.Subscriptions.select');
    Route::post('/user/subscription/setData',[UserController::class,'selectSubscription'])->name('User.Subscription.setData');
    Route::post('/user/subscription/deleteData',[UserController::class,'deleteSubscription'])->name('User.Subscription.deleteData');
    Route::post('/user/payment/select',[UserController::class,'selectPayment'])->name('User.Payment.select');
    Route::post('/user/tickets/book',[TicketController::class,'bookTickets'])->name('User.bookTickets');
    Route::get('/user/tickets/book/list',[TicketController::class,'viewBookedTickets'])->name('User.bookedTickets.view');
    Route::post('/user/booking/cancel',[UserController::class,'cancelBookedTickets'])->name('User.cancelBooking');
});

Route::get('/tickets/status', [StripeController::class, 'handle'])->name('tickets.stripe');
Route::get('/return/{session_id}', [StripeController::class, 'return'])->name('stripe.return');
Route::post('/stripe/embed', [TicketController::class, 'stripeEmbed'])->name('stripe.Embed');
// Route::post('/stripe/return',[TicketController::class,'return'])->name('stripe.return');
Route::post('/tickets/embed/buy',[TicketController::class,'embedBuy'])->name('tickets.embed.buy');
