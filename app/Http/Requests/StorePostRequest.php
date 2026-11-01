<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StorePostRequest extends FormRequest
{
    public function attributes(): array
    {
        return [
            'title' => 'Sarlavha',
            'short_content' => 'Qisqa mazmun',
            'content' => 'Mazmun',
            'price' => 'Narx',
            'duration' => 'Ish vaqti',
        ];
    }

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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|max:255',
            'category_id' => 'required|exists:categories,id',
            'short_content' => 'required',
            'content' => 'required',
            'price' => 'required|numeric',
            'duration' => 'required|numeric',
            'image' => 'required|image|max:2048',
            'image_hover' => 'nullable|image|max:2048',
            'gallery_images'   => 'nullable|array',
            'gallery_images.*' => 'image|max:2048',
        ];
    }
}
