<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class IsAdmin
{
     public function handle($request, Closure $next)
    {
        if (Auth::check() && Auth::user()->user_type === 'Admin') {
            return $next($request);
        }

        abort(403, 'Unauthorized');
    }
}
