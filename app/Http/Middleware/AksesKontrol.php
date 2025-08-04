<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AksesKontrol
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
        if (!session()->has('kode_akses_valid')) {
            return redirect()->route('akses.kode')->withErrors(['kode_akses' => 'Silakan masukkan kode akses terlebih dahulu.']);
        }

        return $next($request);
    }
}
