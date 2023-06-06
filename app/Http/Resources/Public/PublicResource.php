<?php

namespace App\Http\Resources\Public;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PublicResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'book_code' => $this->book_code,
            'title' => $this->title,
            'slug' => $this->slug,
            'status' => $this->status,
            'cover' => $this->cover,
            'created_at' => Carbon::parse($this->created_at)->format('Y-m-d')
        ];
    }
}
