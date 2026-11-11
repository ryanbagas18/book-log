<?php

namespace App\Http\Requests;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class StoreRentalRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'fulfillment_method' => ['required', 'in:delivery,pickup'],
            'start_date' => ['required', 'date', 'after_or_equal:today'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'terms_accepted' => ['accepted'],
            'proof_image' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'mimetypes:image/jpeg,image/png,image/webp', 'max:5120'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            if (! $this->filled(['start_date', 'end_date'])) {
                return;
            }

            $days = $this->rentalDays();
            if ($days > config('rental.max_days')) {
                $validator->errors()->add(
                    'end_date',
                    'Rental period cannot exceed ' . config('rental.max_days') . ' days.'
                );
            }
        });
    }

    public function rentalDays(): int
    {
        $start = Carbon::parse($this->input('start_date'));
        $end = Carbon::parse($this->input('end_date'));

        return (int) $start->diffInDays($end) + 1;
    }
}
