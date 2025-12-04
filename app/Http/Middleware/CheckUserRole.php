<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class CheckUserRole
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::user()->role === 'admin') {
            return $next($request);
        }
        throw new AccessDeniedHttpException;
    }
}
