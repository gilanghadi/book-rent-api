<?php

namespace App\Http\Resources\Dashboard;

use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DatacountResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'book_count' => $this->count(),
            'category_count' => Category::count(),
            'user_count' => User::count(),
        ];
    }
}
