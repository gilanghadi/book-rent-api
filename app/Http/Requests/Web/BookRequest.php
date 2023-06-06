<?php

namespace App\Http\Requests\Web;

use App\Models\Book;
use Illuminate\Foundation\Http\FormRequest;

class BookRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'book_code' => 'required|unique:books',
            'title' => 'required',
            'cover' => 'required|image|mimes:png,jpg,jpeg'
        ];
    }
}
