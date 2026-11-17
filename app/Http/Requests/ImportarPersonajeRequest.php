<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ImportarPersonajeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'swapi_id' => ['required', 'integer', 'between:1,83', Rule::unique('personajes', 'swapi_id')],
        ];
    }

    public function messages(): array
    {
        return [
            'swapi_id.required' => 'Ingresa el ID del personaje.',
            'swapi_id.integer' => 'El ID debe ser un número entero.',
            'swapi_id.between' => 'El ID debe estar entre 1 y 83.',
            'swapi_id.unique' => 'Este personaje ya fue importado.',
        ];
    }
}
