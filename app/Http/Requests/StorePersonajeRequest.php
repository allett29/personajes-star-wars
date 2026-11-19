<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Valida los datos al crear un personaje de forma manual (no SWAPI).
 */
class StorePersonajeRequest extends FormRequest
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
            'genero' => ['required', 'string', 'in:masculino,femenino,n/a'],
            'planeta_origen' => ['nullable', 'string', 'max:100'],
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre es obligatorio.',
            'nombre.min' => 'El nombre debe tener al menos 3 caracteres.',
            'genero.in' => 'El género debe ser masculino, femenino o n/a.',
        ];
    }
}