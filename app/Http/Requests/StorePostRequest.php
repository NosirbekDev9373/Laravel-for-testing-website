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
            'category_id' => 'Kategoriya',
            'short_content' => 'Qisqa mazmun',
            'content' => 'Mazmun',
            'price' => 'Narx',
            'duration' => 'Ish vaqti',
            'image' => 'Asosiy rasm',
            'image_hover' => 'Hover rasm',
            'gallery_images' => 'Galereya rasmlari',
            'tags' => 'Teglar',
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
        $creating = $this->isMethod('post');

        return [
            'title' => ['required', 'string', 'max:255'],
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'short_content' => ['required', 'string', 'max:1000'],
            'content' => ['required', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'duration' => ['required', 'integer', 'min:0'],
            'image' => [$creating ? 'required' : 'nullable', 'image', 'max:5120'],
            'image_hover' => ['nullable', 'image', 'max:5120'],
            'gallery_images' => ['nullable', 'array'],
            'gallery_images.*' => ['image', 'max:5120'],
            'tags' => ['nullable', 'array'],
            'tags.*' => ['integer', 'distinct', 'exists:tags,id'],
        ];
    }
}
