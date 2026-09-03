<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\User;
use Symfony\Component\HttpFoundation\Response;

class CheckGuruToken
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->bearerToken() ?? $request->header('Authorization');

        if (!$token) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Akses ditolak! Token autentikasi Guru tidak disertakan.',
            ], 401);
        }

        $token = trim(str_replace('Bearer ', '', $token));
        $user = User::where('api_token', $token)
                    ->where('role', 'guru')
                    ->first();

        if (!$user) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Akses ditolak! Sesi Guru tidak valid atau telah kedaluwarsa.',
            ], 403);
        }

        // Lampirkan data user ke request
        $request->merge(['authenticated_guru' => $user]);

        return $next($request);
    }
}
