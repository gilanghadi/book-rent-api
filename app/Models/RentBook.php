<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RentBook extends Model
{
    use HasFactory;

    protected $table = 'rent_logs';
    protected $fillable = [
        'user_id',
        'book_id',
        'book_rent',
        'return_date'
    ];


    public function rent_book()
    {
        return $this->belongsTo(Book::class, 'book_id', 'id');
    }

    public function rent_client()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
