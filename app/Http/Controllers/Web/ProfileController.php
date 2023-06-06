<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Resources\Rentbook\RentBookDetailResource;
use App\Models\RentBook;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function index()
    {
        $user = RentBook::where('user_id', Auth::id())->get();
        return response()->json([
            'status' => 'true',
            'data' => RentBookDetailResource::collection($user->loadMissing(['rent_book:id,book_code,title,cover']))
        ]);
    }
}
