<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsNotLogged
{
    public function handle(Request $request, Closure $next): Response
    {
        if (session("id") <= 0 || session("active") == false || !session("active") ) {
            throw new HttpResponseException(response()->json([
                'message' => "You are logged"
            ], 419));
        }

        return $next($request);
    }
}
