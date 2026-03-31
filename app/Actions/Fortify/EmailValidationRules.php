<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Validation\Rule;

trait EmailValidationRules
{

    /**
     * Agrega las reglas de validacion para un email
     */
    protected function emailValidationRules(): array
    {
        return ['required', 'string', 'email', 'max:255', Rule::unique(User::class, 'email')];
    }


    /**
     * Saca los mensajes de la validacion del email
     */
    protected function emailMessages(): array
    {
        return [
            'required' => 'El campo correo electronico no esta completo',
            'string' => 'Existe un error en el campo correo electronico. Intentelo de nuevo',
            'email' => 'El campo correo electronico esta mal formado',
            'max' => 'El campo correo electronico no puede ser tan largo',
            'unique' => 'Ese correo electronico ya esta registrado'
        ];
    }
}
