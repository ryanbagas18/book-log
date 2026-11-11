<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreLibraryBookRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'genre_id' => ['required', 'exists:genres,id'],
            'title' => ['required', 'string', 'max:255'],
            'author' => ['nullable', 'string', 'max:255'],
            'isbn' => ['nullable', 'string', 'max:20'],
            'synopsis' => ['nullable', 'string'],
            'goodreads_url' => ['nullable', 'url', 'max:500'],
            'language' => ['required', 'string', 'max:50'],
            'pages' => ['nullable', 'integer', 'min:1', 'max:65535'],
            'cover_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'deposit_price' => ['nullable', 'integer', 'min:0'],
            'daily_price' => ['required', 'integer', 'min:1'],
            'stock' => ['nullable', 'integer', 'min:0', 'max:65535'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }
}
