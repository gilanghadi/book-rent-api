<?php

namespace App\Http\Controllers\Authenticated;

use App\Http\Controllers\Controller;
use App\Http\Requests\Authenticated\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function store(RegisterRequest $request)
    {
        $validator = $request->validated();
        $validator['password'] = Hash::make($validator['password']);
        $user = User::create($validator);
        if (!$user) {
            return response()->error('Terjadi Kesalahan Saat Mendaftar!', 500);
        }
        return response()->success('Register Berhasil!', 201);
    }
}
