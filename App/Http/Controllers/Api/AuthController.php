<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|min:6',
            'email' => 'required|unique:users|email',
            'password' => 'required|min:6|string'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->passowrd),
        ]);

        $token = $user->createToken('api_token')->plainTextToken;

        return response()->json([
            'status' => 'Succes',
            'message' => 'Anda Berhasil Regist',
            'token' => $token,
            'user'=> $user
        ], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = User::where('email', $request->email)->first();

        if(!$user || Hash::check($request->password, $user->password)){
            throw ValidationException::withMessages([
                'email' => ['Mencurigakan??'],
            ]);
        }

        $token = $user->createToken('api_token')->plainTextToken;

        return response()->json([
            'status' => 'Succes',
            'message' => 'Login Berhasil!!',
            'token' => $token,
            'user' => $user
        ], 201);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'status' => 'Succes',
            'message' => 'Anda Telah Logout',
        ]);
    }
}
