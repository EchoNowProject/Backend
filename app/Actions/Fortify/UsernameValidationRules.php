<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Validation\Rule;

trait UsernameValidationRules
{

    /**
     * Agrega las reglas de validacion para un nombre de usuario
     */
    protected function usernameValidationRules(): array
    {
        return ['required', 'string', 'max:255', Rule::unique(User::class, 'username')];
    }


    /**
     * Saca los mensajes de la validacion del nombre de usuario
     */
    protected function usernameMessages(): array
    {
        return [
            'required' => 'El campo nombre de usuario es obligatorio',
            'string' => 'El campo nombre de usuario debe de ser una cadena de texto',
            'max' => 'El campo nombre de usuario no debe de superar los 255 caracteres',
            'unique' => 'Ya existe otro usuario con ese nombre',
        ];
    }
}
