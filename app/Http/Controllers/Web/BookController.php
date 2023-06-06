<?php

namespace App\Http\Controllers\Web;

use App\Models\Book;
use App\Models\User;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\Web\BookRequest;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\Book\BookResource;
use App\Http\Resources\Book\BookDetailResource;
use App\Http\Resources\Rentbook\RentBookDetailResource;
use App\Http\Resources\RentBook\RentBookResource;
use App\Http\Resources\RentBook\ReturnBookResource;
use App\Models\RentBook;
use Carbon\Carbon;

class BookController extends Controller
{
    public function index()
    {
        $books = Book::all();
        return response()->json([
            'status' => 'true',
            'data' => [
                'books' => BookResource::collection($books),
                'books_deleted' => BookDetailResource::collection($books)
            ]
        ]);
    }

    public function store(BookRequest $request)
    {
        $validator = $request->validated();

        if ($request->file('cover')) {
            $validator['cover'] = $request->file('cover')->store('image', 'public');
        }

        $book = Book::create($validator);
        $book->categories()->sync([$request->categories]);
        if (!$book) {
            return response()->json([
                'status' => 'false',
                'message' => 'Gagal Menambahkan Buku!',
                'data' =>  new BookDetailResource($book->loadMissing(['categories:id,name']))
            ]);
        }
        return response()->json([
            'status' => 'true',
            'message' => 'Menambahkan Buku Berhasil!',
            'data' => new BookDetailResource($book->loadMissing(['categories:id,name']))
        ]);
    }

    public function update(Request $request, Book $book)
    {
        $book->book_code = $request->book_code;
        $book->title = $request->title;
        $book->slug = Str::slug(strtolower($request->title), "-");
        if ($request->hasFile('cover')) {
            if (Storage::exists($book->cover)) {
                Storage::delete('image/' . $book->cover);
            }
            $book->cover = $request->file('cover')->store('image', 'public');
        }
        $book->update();
        if ($request->categories) {
            $book->categories()->sync([$request->categories]);
        }
        if (!$book) {
            return response()->error('Gagal Mengupdate Kategori');
        }
        return response()->json([
            'status' => 'true',
            'message' => 'Mengupdate Kategori Berhasil!',
            'data' => new BookDetailResource($book->loadMissing(['categories:id,name']))
        ]);
    }


    public function destroy(Book $book)
    {
        $book = Book::findOrFail($book->id);
        if ($book->cover) {
            Storage::delete($book->cover);
        }
        $book->delete();
        if (!$book) {
            return response()->error('Gagal Menghapus Buku!');
        }
        return response()->json([
            'status' => 'true',
            'message' => 'Berhasil Menghapus Buku!',
            'data' => new BookDetailResource($book->loadMissing(['categories:id,name']))
        ]);
    }




    // rent book


    public function rent_book()
    {
        $rentBooks = RentBook::all();
        return response()->json([
            'status' => 'true',
            'data' => RentBookResource::collection($rentBooks->loadMissing(['rent_client:id,username,phone,email,status', 'rent_book:id,book_code,title,cover'])),
        ]);
    }


    public function rent_book_store(Request $request)
    {
        $request['book_rent'] = Carbon::now()->format('Y-m-d');
        $request['return_date'] = Carbon::now()->addDays(3)->format('Y-m-d');

        $book = Book::findOrFail($request->book_id)->only('status');
        if ($book['status'] != 'in_stock') {
            return response()->json([
                'status' => 'false',
                'message' => 'Saat Ini Buku Yang Dipilih Sedang Tidak Tersedia'
            ]);
        } else {
            $count = RentBook::where('user_id', $request->user_id)->where('actual_return_date', null)->count();

            if ($count >= 3) {
                return response()->json([
                    'status' => 'false',
                    'message' => 'Anda Sudah Melebihi Dari Limit Buku Yang Dipinjamkan'
                ]);
            } else {
                try {
                    DB::beginTransaction();

                    RentBook::create($request->all());
                    $book = Book::findOrFail($request->book_id);
                    $book->status = 'not_available';
                    $book->save();
                    DB::commit();
                    return response()->json([
                        'status' => 'true',
                        'message' => 'Peminjaman Buku Berhasil Dan Sudah Diproses'
                    ]);
                } catch (\Throwable $th) {
                    DB::rollBack();
                    return $th;
                }
            }
        }
    }




    // return book

    public function return_book()
    {
        $users = User::where('id', 2)->where('status', 'active')->get();
        $book = Book::all();
        return response()->json([
            'status' => 'true',
            'user_data' => $users,
            'book_data' => BookResource::collection($book),
        ]);
    }

    public function return_book_store(Request $request)
    {
        $return = RentBook::where('user_id', $request->user_id)->where('book_id', $request->book_id)->where('actual_return_date', null);
        $return_first = $return->first();
        $return_count = $return->count();

        if ($return_count != 1) {
            return response()->json([
                'status' => 'false',
                'message' => 'Buku Sudah Dikembalikan!',
            ]);
        }

        $return_first->actual_return_date = Carbon::now()->format('Y-m-d');
        $return_first->save();
        return response()->json([
            'status' => 'true',
            'message' => 'Berhasil Mengembalikan Buku!',
        ]);
    }
}
