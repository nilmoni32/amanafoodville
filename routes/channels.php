<?php


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

Broadcast::channel('order.placed', function(\App\Models\Order $order) {
    return Auth::guard('web')->user()->id === $order->user_id;
}, ['guards' => ['web','admin']]);
