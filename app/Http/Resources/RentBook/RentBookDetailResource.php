<?php

namespace App\Http\Resources\Rentbook;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RentBookDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $returnData = Carbon::parse($this->return_date)->format('Y-m-d');
        $actualReturnDate = ($this->actual_return_date == null ? 'Belum Melewati Batas Pengembalian' : ($returnData < $this->actual_return_date ? 'Melewati Batas Pengembalian/DENDA' : 'Belum Melewati Batas Pengembalian'));
        return [
            'id' => $this->id,
            'rent_book' => $this->whenLoaded('rent_book', function () {
                return collect($this->rent_book)->each(function ($book) {
                    return $book;
                });
            }),
            'book_rent' => Carbon::parse($this->book_rent)->format('Y-m-d'),
            'return_date' => $returnData,
            'actual_return_date' => $actualReturnDate,
            'created_at' => Carbon::parse($this->created_at)->format('Y-m-d'),
        ];
    }
}
