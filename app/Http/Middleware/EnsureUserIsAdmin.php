<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdmin
{
    /**
     * Memastikan hanya pengguna dengan peran admin yang dapat mengakses rute.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user() || $request->user()->role !== 'admin') {
            abort(403, 'Akses terbatas hanya untuk administrator.');
        }

        return $next($request);
    }
}
