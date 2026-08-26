<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('kategori'));
    }

    public function rules(): array
    {
        $categoryId = $this->route('kategori')?->id;

        return [
            'name'        => 'required|string|max:255',
            'slug'        => ['nullable', 'string', 'max:255', Rule::unique('categories', 'slug')->ignore($categoryId)],
            'parent_id'   => ['nullable', 'exists:categories,id', Rule::notIn([$categoryId])],
            'description' => 'nullable|string',
            'icon'        => 'nullable|string|max:50',
            'sort_order'  => 'nullable|integer|min:0',
            'is_active'   => 'nullable|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'    => 'Nama kategori wajib diisi.',
            'slug.unique'      => 'Slug kategori sudah digunakan.',
            'parent_id.not_in' => 'Kategori tidak boleh menjadi induk dari dirinya sendiri.',
        ];
    }
}
