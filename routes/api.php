<?php

use App\Http\Controllers\Authenticated\LoginController;
use App\Http\Controllers\Authenticated\RegisterController;
use App\Http\Controllers\Web\BookController;
use App\Http\Controllers\Web\CategoryController;
use App\Http\Controllers\Web\ClientController;
use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\ProfileController;
use App\Http\Controllers\Web\PublicController;
use App\Http\Controllers\Web\UserController;
use App\Models\Role;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


Route::get('/public', [PublicController::class, 'index']);

Route::middleware('guest')->group(function () {
    Route::post('/login', [LoginController::class, 'store']);
    Route::post('/register', [RegisterController::class, 'store']);
});

Route::middleware(['auth:sanctum'])->group(function () {

    Route::middleware(['auth', 'admin'])->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index']);

        // books
        Route::get('/books', [BookController::class, 'index']);
        Route::post('/books', [BookController::class, 'store']);
        Route::patch('/books/{book:slug}', [BookController::class, 'update']);
        Route::delete('/books/{book:slug}', [BookController::class, 'destroy']);

        // rent_book
        Route::get('/rent-book', [BookController::class, 'rent_book']);
        Route::post('/rent-book', [BookController::class, 'rent_book_store']);

        // return_book
        Route::get('/return-book', [BookController::class, 'return_book']);
        Route::post('/return-book', [BookController::class, 'return_book_store']);

        // categories
        Route::get('/categories', [CategoryController::class, 'index']);
        Route::post('/categories', [CategoryController::class, 'store']);
        Route::patch('/categories/{category:slug}', [CategoryController::class, 'update']);
        Route::delete('/categories/{category:slug}', [CategoryController::class, 'destroy']);
        Route::get('/categories/{category:slug}', [CategoryController::class, 'restore']);

        // users
        Route::get('/client', [ClientController::class, 'index']);
        Route::get('/registered', [ClientController::class, 'registered']);
        Route::get('/registered-user/{user:slug}', [ClientController::class, 'registered_user']);
        Route::get('/bans/{user:slug}', [ClientController::class, 'bans']);
        Route::get('/unbans/{user:slug}', [ClientController::class, 'unbans']);
        Route::get('/client-banned', [ClientController::class, 'client_banned']);
    });

    Route::middleware(['auth', 'client', 'auth.banned'])->group(function () {
        // books
        Route::get('/books', [BookController::class, 'index']);

        // profile
        Route::get('/profile', [ProfileController::class, 'index']);
    });

    Route::get('/logout', [LoginController::class, 'logout']);
});
