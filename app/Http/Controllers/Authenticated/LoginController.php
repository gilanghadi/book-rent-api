<?php

namespace App\Http\Controllers\Authenticated;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Http\Requests\Authenticated\LoginRequest;

class LoginController extends Controller
{
    public function store(LoginRequest $request)
    {
        $validator = $request->validated();

        if (Auth::attempt($validator, $request->remember)) {
            $user = User::where('username', $validator['username'])->first();
            if (!$user || !Hash::check($validator['password'], $user->password)) {
                throw ValidationException::withMessages([
                    'error' => ['The provided credentials are incorrect.'],
                ]);
            }
            $user->createToken('login')->plainTextToken;
            return response()->json([
                'status' => true,
                'message' => 'Login Berhasil!'
            ], 200);
        }
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->success('Logout Berhasil!', 200);
    }
}
