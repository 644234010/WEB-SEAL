<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShippingMiddleware
{

    public function handle(Request $request, Closure $next)
    {
        if (auth()->check() && auth()->user()->isShipping()) {
            return $next($request);
        }
    
        abort(403, 'Unauthorized action.');
    }
    
}
