<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminAuth
{
    public function handle(Request $request, Closure $next)
    {
        // cek session admin
        if (!session()->has('admin_id')) {
            return redirect()->route('login')->with('error', 'Silakan login dulu!');
        }

        return $next($request);
    }
}