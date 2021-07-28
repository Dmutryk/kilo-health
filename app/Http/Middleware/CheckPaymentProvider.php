<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckPaymentProvider
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        //TODO implement check is request from the right source
        // I guess we could do it by checking password from responseBody
        // password string: The same value as the shared secret you submit in the password field
        // of the requestBody when validating receipts.
        return $next($request);
    }
}
