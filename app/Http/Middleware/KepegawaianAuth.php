<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class KepegawaianAuth
{
    public function handle(Request $request, Closure $next)
    {
        // cek session kepegawaian
        if (!session()->has('kepegawaian_id')) {
            return redirect()->route('login')->with('error', 'Silakan login dulu!');
        }

        return $next($request);
    }
}