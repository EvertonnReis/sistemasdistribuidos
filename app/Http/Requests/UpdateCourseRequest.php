<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCourseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $courseId = $this->route('course');
        
        return [
            'category_id' => ['sometimes', 'exists:categories,id'],
            'title' => ['sometimes', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'unique:courses,slug,' . $courseId],
            'description' => ['nullable', 'string'],
            'duration_hours' => ['nullable', 'integer', 'min:0'],
            'price' => ['nullable', 'numeric', 'min:0'],
            'is_published' => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'category_id.exists' => 'A categoria selecionada não existe.',
            'title.max' => 'O título não pode ter mais de 255 caracteres.',
            'slug.unique' => 'Este slug já está em uso.',
            'duration_hours.integer' => 'A duração deve ser um número inteiro.',
            'duration_hours.min' => 'A duração deve ser no mínimo 0.',
            'price.numeric' => 'O preço deve ser um número.',
            'price.min' => 'O preço deve ser no mínimo 0.',
        ];
    }
}
