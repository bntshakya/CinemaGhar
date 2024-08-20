<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        //
        'tickets/trigger',
        'stripe/embed',
        'tickets/embed/buy',
        '/create-confirm-intent',
        '/user/subscription/setData'
    ];
}
