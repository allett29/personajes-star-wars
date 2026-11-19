<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Valida los datos al actualizar un personaje existente.
 * Usa las mismas reglas que la creación manual.
 */
class UpdatePersonajeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return (new StorePersonajeRequest())->rules();
    }

    public function messages(): array
    {
        return (new StorePersonajeRequest())->messages();
    }
}