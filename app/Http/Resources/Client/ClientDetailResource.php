<?php

namespace App\Http\Resources\Client;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClientDetailResource extends JsonResource
{

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $format = Carbon::parse($this->created_at);
        $long_banned = Carbon::parse(Carbon::now())->addDays(7);
        return [
            'id' => $this->id,
            'username' => $this->username,
            'slug' => $this->slug,
            'phone' => $this->phone,
            'email' => $this->email,
            'address' => $this->address,
            'status' => $this->status,
            'created_at' => $format->isoFormat('Y-m-d'),
            'long_banned' => $format->isoFormat('Y-m-d') . " - " . $long_banned->isoFormat('Y-m-d'),
            'expired_banned' => $long_banned->isoFormat('Y-m-d'),

        ];
    }
}
