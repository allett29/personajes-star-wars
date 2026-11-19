<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Valida el ID enviado al importar desde SWAPI.
 */
class ImportPersonajeSwapiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'swapi_id' => ['required', 'integer', 'min:1', 'max:83'],
        ];
    }

    public function messages(): array
    {
        return [
            'swapi_id.required' => 'Debe indicar el ID de SWAPI.',
            'swapi_id.min' => 'El ID de SWAPI debe estar entre 1 y 83.',
            'swapi_id.max' => 'El ID de SWAPI debe estar entre 1 y 83.',
        ];
    }
}