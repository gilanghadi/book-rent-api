<?php

namespace App\Http\Controllers\Web;

use App\Models\Book;
use App\Models\User;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\Dashboard\DatacountResource;
use App\Http\Requests\Authenticated\RegisterRequest;

class DashboardController extends Controller
{
    public function index()
    {
        $books = Book::get();
        return new DatacountResource($books);
    }

    public function store(Request $request)
    {
        $user = new User();
        $user->username = $request->username;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->address = $request->address;
        $user->password = Hash::make($request->password);
        $user->role_id = 1;
        $user->save();
        if (!$user) {
            return response()->error('Terjadi Kesalahan Saat Mendaftar!', 500);
        }
        return response()->success('Register Admin Berhasil!', 201);
    }
}
