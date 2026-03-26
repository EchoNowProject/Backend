<?php

namespace App\Actions\Fortify;

use Illuminate\Validation\Rules\Password;

trait PasswordValidationRules
{
    /**
     * Get the validation rules used to validate passwords.
     *
     * @return array<int, \Illuminate\Contracts\Validation\Rule|array<mixed>|string>
     */
    protected function passwordValidationRules(bool $confirmed = false): array
    {
        return ['required', 'string', $confirmed ? 'confirmed' : null, Password::default()];
    }

    protected function passwordMessages(): array
    {
        return [
            'required' => 'El campo contraseña no esta completo',
            'string' => 'Existe un error en el campo contraseñe. Intentelo de nuevo',
            'confirmed' => 'Las contraseñas no coinciden',
            'min' => 'La nueva contraseña requiere al menos 8 carácteres',
        ];
    }
}
