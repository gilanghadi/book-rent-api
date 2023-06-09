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
            $user = Auth::user();
            return response()->json([
                'status' => true,
                '_token' => $user->createToken('login')->plainTextToken,
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
