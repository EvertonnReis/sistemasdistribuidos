<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLessonRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'course_id' => ['required', 'exists:courses,id'],
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string'],
            'content' => ['nullable', 'string'],
            'video_url' => ['nullable', 'url'],
            'duration_minutes' => ['nullable', 'integer', 'min:0'],
            'order' => ['nullable', 'integer', 'min:0'],
            'is_free' => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'course_id.required' => 'O curso é obrigatório.',
            'course_id.exists' => 'O curso selecionado não existe.',
            'title.required' => 'O título é obrigatório.',
            'title.max' => 'O título não pode ter mais de 255 caracteres.',
            'video_url.url' => 'A URL do vídeo deve ser válida.',
            'duration_minutes.integer' => 'A duração deve ser um número inteiro.',
            'duration_minutes.min' => 'A duração deve ser no mínimo 0.',
            'order.integer' => 'A ordem deve ser um número inteiro.',
            'order.min' => 'A ordem deve ser no mínimo 0.',
        ];
    }
}
