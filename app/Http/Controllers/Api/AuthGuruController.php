<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthGuruController extends Controller
{
    /**
     * Login untuk Guru / Admin
     */
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->email)
                    ->where('role', 'guru')
                    ->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Email atau kata sandi salah!',
            ], 401);
        }

        // Generate token akses unik
        $token = Str::random(64);
        $user->api_token = $token;
        $user->save();

        return response()->json([
            'status'  => 'success',
            'role'    => 'guru',
            'message' => 'Login guru berhasil!',
            'token'   => $token,
            'data'    => [
                'id'    => $user->id,
                'name'  => $user->name,
                'email' => $user->email,
                'role'  => $user->role,
            ],
        ], 200);
    }

    /**
     * Logout untuk Guru
     */
    public function logout(Request $request)
    {
        $token = $request->bearerToken() ?? $request->header('Authorization');

        if ($token) {
            $token = str_replace('Bearer ', '', $token);
            $user = User::where('api_token', $token)->first();
            if ($user) {
                $user->api_token = null;
                $user->save();
            }
        }

        return response()->json([
            'status'  => 'success',
            'message' => 'Logout berhasil!',
        ], 200);
    }

    /**
     * Ambil data profil guru yang sedang login
     */
    public function me(Request $request)
    {
        $token = $request->bearerToken() ?? $request->header('Authorization');

        if (!$token) {
            return response()->json(['status' => 'error', 'message' => 'Token tidak disertakan'], 401);
        }

        $token = str_replace('Bearer ', '', $token);
        $user = User::where('api_token', $token)->first();

        if (!$user) {
            return response()->json(['status' => 'error', 'message' => 'Sesi tidak valid'], 401);
        }

        return response()->json([
            'status' => 'success',
            'role'   => 'guru',
            'data'   => [
                'id'    => $user->id,
                'name'  => $user->name,
                'email' => $user->email,
                'role'  => $user->role,
            ],
        ]);
    }
}
