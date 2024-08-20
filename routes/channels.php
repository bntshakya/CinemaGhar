<?php

use App\Models\adminusers;
use App\Models\register;
use Illuminate\Support\Facades\Broadcast;
use App\Models\Order;
use App\Models\User;
use illuminate\Support\Facades\Log;

// Broadcast::channel('orders.{orderId}', function (User $user, int $orderId) {
//     dd('eret');

//     return $user->id === Order::findOrNew($orderId)->user_id;
// });

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

// Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
//     return (int) $user->id === (int) $id;
// });  



Broadcast::channel('movie-revenue', function (adminusers $user) {
    Log::channel('custom')->info('broadcast listener event111');

    // dd($user);

    // // $result = $register->email === $email;
    // // error_log($result ? 'abc' : 'bac'); // This will log 'abc' if true, 'bac' if false
    // Log::info('abc');
    // Log::info('xyz');
    // Log::channel('custom')->info('@ the privatechannel test');
    // Log::channel('custom')->info('uuuu');

    return true;
}, ['guards' => ['users']]);



