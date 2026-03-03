<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SudahNIP
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (!session()->has('password_verified')) {
            return redirect()
                ->route('homepage.menuawal') // route homepage_menuawal
                ->with('error', 'Silakan login password terlebih dahulu.');
        }
        return $next($request);
    }
}