<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class JWTAuthController extends Controller
{
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        $token = JWTAuth::fromUser($user);

        return response()->json([
            'user' => $user,
            'token' => $token
        ], 200);
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $minutes = (int) config('jwt.ttl', 60);

        $cookie = \Illuminate\Support\Facades\Cookie::make(
            'token',
            $token,
            $minutes,
            '/',
            null,
            (bool) config('session.secure', false),
            true,  // HttpOnly
            false,
            config('session.same_site', 'lax') // 'lax', 'strict', or 'none'
        );

        return response()
            ->json([
                'user' => auth()->user(),
                'token' => $token
            ], 200)
            ->withCookie($cookie);
    }

    public function me()
    {
        return response()->json(auth()->user());
    }

    public function logout()
    {
        JWTAuth::invalidate(JWTAuth::getToken());
        return response()->json(['message' => 'Successfully logged out']);
    }

    public function verifyToken(Request $request)
    {
        $token = $request->input('token');

        try {
            $payload = JWTAuth::setToken($token)->getPayload();
            return response()->json(['valid' => true, 'payload' => $payload]);
        } catch (\Exception $e) {
            return response()->json(['valid' => false, 'error' => $e->getMessage()], 401);
        }
    }
}
