<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SeccionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'fk_seccion_id' => 'required|integer|min:0',
            'seccion_key' => 'nullable|string|max:255',
            'fk_sistema_id' => 'required|integer|between:1,7',
            'seccion_grupo' => 'required|string|max:255',
            'seccion_grupo_en' => 'nullable|string|max:255',
            'seccion_grupo_pt' => 'nullable|string|max:255',
            'icono' => 'nullable|string|max:100',
            'seccion_nombre' => 'required|string|max:255',
            'seccion_nombre_en' => 'nullable|string|max:255',
            'seccion_nombre_pt' => 'nullable|string|max:255',
            'seccion_uri' => 'nullable|string|max:255',
            'orden' => 'required|integer|min:0',
            'ordenmt' => 'nullable|integer|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'fk_sistema_id.required' => 'El sistema es obligatorio.',
            'fk_sistema_id.between' => 'El sistema debe ser válido.',
            'seccion_grupo.required' => 'El grupo es obligatorio.',
            'seccion_nombre.required' => 'El nombre es obligatorio.',
            'orden.required' => 'El orden es obligatorio.',
        ];
    }
}
