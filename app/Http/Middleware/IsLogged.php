<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsLogged
{
    public function handle(Request $request, Closure $next): Response
    {
        if (session("id") >= 1 || session("active")) {
            return $next($request);
        }

        
        throw new HttpResponseException(response()->json([
            'message' => "You are not logged"
        ], 400));
    }
}
