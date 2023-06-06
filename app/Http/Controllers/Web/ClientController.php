<?php

namespace App\Http\Controllers\Web;

use App\Models\User;
use App\Models\RentBook;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Client\ClientResource;
use App\Http\Resources\RentBook\RentBookResource;
use App\Http\Resources\Client\ClientDetailResource;

class ClientController extends Controller
{
    public function index()
    {
        $users = User::where('role_id', 2)->where('status', 'active')->get();
        $bannedUsers = User::banned()->get();
        $rentBooks = RentBook::get();
        return response()->json([
            'status' => 'true',
            'data' => [
                'users' => ClientResource::collection($users),
                'users_banned' => ClientDetailResource::collection($bannedUsers),
                'rent_logs' => RentBookResource::collection($rentBooks->loadMissing(['rent_client:id,username,phone,email,status', 'rent_book:id,book_code,title,cover'])),
            ]
        ]);
    }

    public function registered()
    {
        $users = User::where('role_id', 2)->where('status', 'in_active')->get();
        return response()->json([
            'status' => 'true',
            'data' => [
                'users' => ClientResource::collection($users)
            ]
        ]);
    }

    public function registered_user(User $user)
    {
        $user->status = 'active';
        $user->save();
        return response()->json([
            'status' => 'true',
            'message' => 'Merubah Status Client Dari In_active Menjadi Active Berhasil!',
            'data' => new ClientResource($user)
        ]);
    }


    public function bans(User $user)
    {
        $user->banUntil('7 days');
        return response()->json([
            'status' => 'true',
            'message' => 'Banned User Berhasil!',
            'user' => new ClientDetailResource($user)
        ]);
    }

    public function unbans(User $user)
    {
        $user->unban();
        return response()->json([
            'status' => 'true',
            'message' => 'Unbaned User Berhasil!',
            'user' => new ClientDetailResource($user)
        ]);
    }

    public function client_banned()
    {
        $bannedUser = User::banned()->get();
        return response()->json([
            'status' => 'true',
            'data' =>  ClientDetailResource::collection($bannedUser)
        ]);
    }
}
