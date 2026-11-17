<?php

namespace App\Http\Requests;

use App\Models\Personaje;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PersonajeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nombre' => ['required', 'string', 'min:3', 'max:100'],
            'altura' => ['nullable', 'integer', 'min:1', 'max:300'],
            'masa' => ['nullable', 'integer', 'min:1', 'max:500'],
            'color_cabello' => ['nullable', 'string', 'max:100'],
            'color_ojos' => ['nullable', 'string', 'max:100'],
            'genero' => ['required', Rule::in(Personaje::GENEROS)],
            'planeta_origen' => ['nullable', 'string', 'max:100'],
        ];
    }

    public function messages(): array
    {
        return [
            'required' => 'El campo :attribute es obligatorio.',
            'string' => 'El campo :attribute debe ser texto.',
            'integer' => 'El campo :attribute debe ser un número entero.',
            'nombre.min' => 'El nombre debe tener al menos 3 caracteres.',
            'altura.min' => 'La altura debe ser un número positivo.',
            'masa.min' => 'La masa debe ser un número positivo.',
            'altura.max' => 'La altura no puede ser mayor a 300 cm.',
            'masa.max' => 'La masa no puede ser mayor a 500 kg.',
            'max' => 'El campo :attribute no puede tener más de 100 caracteres.',
            'genero.in' => 'El género debe ser masculino, femenino o n/a.',
        ];
    }

    public function attributes(): array
    {
        return [
            'nombre' => 'nombre',
            'altura' => 'altura',
            'masa' => 'masa',
            'color_cabello' => 'color de cabello',
            'color_ojos' => 'color de ojos',
            'genero' => 'género',
            'planeta_origen' => 'planeta de origen',
        ];
    }
}
