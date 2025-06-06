<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect('/login');
        }

        // Pastikan user adalah admin
        if (Auth::user()->role !== 'admin') {
            return abort(403, 'Akses ditolak.');
        }

        // Jika user admin mencoba masuk ke /timkerja, blokir akses
        if ($request->is('timkerja/*')) {
            return abort(403, 'Akses ditolak.');
        }

        return $next($request);
    }
}
