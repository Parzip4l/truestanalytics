<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class Authenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Periksa apakah ada data sesi yang menandakan pengguna telah login
        if (Session::has('userData')) {
            // Jika sesi ada, lanjutkan permintaan
            return $next($request);
        }

        // Jika tidak ada, redirect ke halaman login
        return redirect('login');
    }
}
