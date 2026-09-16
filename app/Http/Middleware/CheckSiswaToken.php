<?php

namespace App\Http\Middleware;

use App\Models\Siswa;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckSiswaToken
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->bearerToken() ?? $request->header('Authorization');

        if (! $token) {
            return response()->json([
                'status' => 'error',
                'message' => 'Akses ditolak! Token autentikasi Siswa tidak disertakan.',
            ], 401);
        }

        $token = trim(str_replace('Bearer ', '', $token));
        $siswa = Siswa::where('api_token', $token)->first();

        if (! $siswa) {
            return response()->json([
                'status' => 'error',
                'message' => 'Akses ditolak! Sesi Siswa tidak valid atau telah kedaluwarsa.',
            ], 401);
        }

        // Simpan instance siswa ke request dan auth guard
        $request->merge(['authenticated_siswa' => $siswa]);
        auth('siswa')->setUser($siswa);

        return $next($request);
    }
}
