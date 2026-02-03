<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'completed' => 'required|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'O título é obrigatório',
            'title.max' => 'O título não pode ter mais de 255 caracteres',
            'description.required' => 'A descrição é obrigatória',
            'completed.required' => 'O status de completude é obrigatório',
            'completed.boolean' => 'O status de completude deve ser verdadeiro ou falso',
        ];
    }
}
