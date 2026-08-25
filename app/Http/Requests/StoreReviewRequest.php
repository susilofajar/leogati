<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreReviewRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'rating'  => ['required', 'integer', 'between:1,5'],
            'title'   => ['nullable', 'string', 'max:100'],
            'comment' => ['required', 'string', 'min:10', 'max:2000'],
        ];
    }

    public function messages(): array
    {
        return [
            'rating.required'  => 'Bintang rating 1 hingga 5 wajib dipilih.',
            'rating.between'   => 'Rating harus bernilai antara 1 sampai 5 bintang.',
            'comment.required' => 'Ulasan / pengalaman belanja wajib ditulis.',
            'comment.min'      => 'Isi ulasan minimal 10 karakter.',
        ];
    }
}
