<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\ResetsUserPasswords;

class ResetUserPassword implements ResetsUserPasswords
{
    use PasswordValidationRules;

    /**
     * Validate and reset the user's forgotten password.
     *
     * @param User $user, array $input
     */
    public function reset(User $user, array $input): array
    {

        if (!Hash::check($input['actualPassword'], $user->password)) {
            return [
                'success' => false,
                'message' => 'La contraseña actual no es correcta.',
                'httpStatus' => 400,
            ];
        }

        $validator = Validator::make($input, [
            'actualPassword' => $this->passwordValidationRules(),
            'newPassword' => $this->passwordValidationRules(true),
        ], [
            'actualPassword' => $this->passwordMessages(),
            'newPassword' => $this->passwordMessages(),
        ]);

        if ($validator->fails()) {
            return [
                'success' => false,
                'message' => $validator->messages()->first(),
                'httpStatus' => 422,
            ];
        }

        $user->update([
            'password' => Hash::make($input['newPassword']),
        ]);

        return [
            'success' => true,
            'message' => 'Contraseña actualizada correctamente.',
            'httpStatus' => 200,
        ];
    }
}
