<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Authenticate
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->user()) {
            return redirect('/login')->with('error', 'Please login first.');
        }
        return $next($request);
    }
}