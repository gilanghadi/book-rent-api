<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Resources\Public\PublicResource;
use App\Models\Book;
use Illuminate\Http\Request;

class PublicController extends Controller
{
    public function index()
    {
        $books = Book::all();
        return response()->json([
            'status' => 'true',
            'data' => PublicResource::collection($books)
        ]);
    }
}
