<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBookRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'sometimes|required|string|max:255',
            'author' => 'nullable|string|max:255',
            'genre' => 'nullable|string|max:255',
            'language' => 'sometimes|required|string|max:50',
            'pages' => 'nullable|integer|min:1|max:65535',
            'isbn' => 'nullable|string|max:20',
            'status' => 'sometimes|required|in:to_read,reading,finished,dropped',
            'rating' => 'nullable|integer|between:1,5',
            'started_at' => 'nullable|date',
            'finished_at' => 'nullable|date|after_or_equal:started_at',
            'ownership' => 'sometimes|required|in:owned,skimmed,borrowed,wishlist',
            'purchase_place' => 'nullable|string|max:255',
            'condition' => 'nullable|in:new,preloved|required_if:ownership,owned',
            'purchase_date' => 'nullable|date',
            'price' => 'nullable|integer|min:0',
            'cover_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'cover_url' => 'nullable|url|max:500',
            'notes' => 'nullable|string',
        ];
    }
}
