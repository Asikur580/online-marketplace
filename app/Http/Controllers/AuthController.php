<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8|confirmed',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }

            $user = User::create([
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => $request->role ?? 'buyer', // Default role is 'buyer'
            ]);

            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json(['message' => 'User create successfully', 'access_token' => $token, 'token_type' => 'Bearer'], 201);
        } catch (Exception $e) {

            return response()->json(['message' => $e->getMessage()]);
        }
    }

    public function login(Request $request)
    {
        try {

            if (!auth()->attempt($request->only('email', 'password'))) {
                return response()->json(['message' => 'Invalid login details'], 401);
            }

            $user = User::where('email', $request->email)->firstOrFail();
            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json(['message' => 'Login successfully', 'access_token' => $token, 'token_type' => 'Bearer']);
        } catch (Exception $e) {

            return response()->json(['message' => $e->getMessage()]);
        }
    }
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json(['message' => 'Logged out successfully']);
    }
}
