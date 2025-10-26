<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class JWTAuthController extends Controller
{
    public function register(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string',
                'email' => 'required|email|unique:users',
                'password' => 'required|string|min:8|confirmed',
                // optional avatar/image upload
                'image' => 'nullable|file|image|max:5120', // 5 MB
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

        // Handle optional image upload to S3 under users/{id}/
        $file = $request->file('image');
        if ($file && $file->isValid()) {
            try {
                $extension = $file->getClientOriginalExtension() ?: 'jpg';
                $filename = uniqid('user_') . '.' . $extension;

                /** @var \Illuminate\Filesystem\FilesystemAdapter $s3disk */
                $s3disk = Storage::disk('s3');
                $path = $s3disk->putFileAs('users/' . $user->id, $file, $filename, ['visibility' => 'private']);

                if (is_string($path) && strlen(trim($path)) > 0) {
                    $url = $s3disk->url($path);
                    $user->image_url = $url;
                    $user->save();
                } else {
                    Log::error('S3 returned empty path when uploading user image', ['user_id' => $user->id, 'original_name' => $file->getClientOriginalName()]);
                }
            } catch (\Exception $e) {
                Log::error('Error uploading user image to S3', ['user_id' => $user->id, 'error' => $e->getMessage()]);
                // continue — user created but image failed
            }
        }

        return response()->json([
            'user' => $user->fresh(),
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

    public function userInfo(){
        $userid = auth()->id();
        $user = User::where('id', $userid)->with('discounts', 'products', 'votes')->first();
        return response()->json($user);
    }

    public function getUserInfo($id){
        $user = User::with('discounts', 'products', 'votes')->find($id);
        if(!$user){
            return response()->json(['message' => 'User not found'], 404);
        }
        return response()->json($user);
    }

    public function getRanking(){
        $users = User::orderBy('xp', 'desc')->take(3)->get(['id', 'name', 'xp', 'image_url']);
        return response()->json($users);
    }
}
