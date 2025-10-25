<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class JWTAuthController extends Controller
{
    public function register(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string',
                'email' => 'required|email|unique:users',
                'password' => 'required|string|min:8|confirmed',
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        }

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

        $minutes = (int) (getenv('JWT_TTL') ?: 60 * 24 * 7); // <-- Usando getenv con un default

        $cookie = Cookie::make('token', $token, $minutes, '/',
            getenv('DOMAIN') ?: null, // Default a 'null'
            (bool) (getenv('SECURE_COOKIE') ?: false), // Default a 'false'
            true,  // HttpOnly
            false,
            getenv('SAME_SITE_COOKIE') ?: 'lax' // Default a 'lax'
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
