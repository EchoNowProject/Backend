<?php

namespace App\Actions\Fortify;

use App\Models\User;
use App\Models\UserNotificationSetting;
use App\Models\UserPrivacySetting;
use App\Models\UserSetting;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules, EmailValidationRules, UsernameValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): array
    {
        $validations = $this->validations($input);
        if ($validations['success']) {

            $creationUser = $this->createUser($input);

            if ($creationUser) {
                return [
                    'success' => true,
                    'message' => 'El usuario se ha creado con exito',
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'El Usuario no se ha podido crear, contacte con un administrador',
                ];
            }
        }

        return [
            'success' => false,
            'message' => $validations['message'],
        ];
    }


    /**
     * Funcion que crea el usuario y sus configuraciones por defecto
     * @param array $input
     * @return bool
     */
    protected function createUser(array $input): bool
    {
        DB::beginTransaction();
        try {
            $user = User::create([
                'username' => $input['username'],
                'email' => $input['email'],
                'password' => Hash::make($input['password']),
                'display_name' => $input['username'],
                'status' => 1,
                'plan' => 1,
            ]);

            UserSetting::create(['user_id' => $user->id]);
            UserNotificationSetting::create(['user_id' => $user->id]);
            UserPrivacySetting::create(['user_id' => $user->id]);

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            Log::debug($e);
            return false;
        }

        return true;
    }

    /**
     * Funcion encargada de aplicar las validaciones sobre la creacion de un usuario
     * @param array $input
     * @return array{message: string, success: bool|array{success: bool}}
     */
    protected function validations(array $input): array
    {
        $validator = Validator::make($input, [
            'username' => $this->usernameValidationRules(),
            'email' => $this->emailValidationRules(),
            'password' => $this->passwordValidationRules(),
        ], [
            'username' => $this->usernameMessages(),
            'email' => $this->emailMessages(),
            'password' => $this->passwordMessages(),
        ]);

        if ($validator->fails()) {
            return [
                'success' => false,
                'message' => $validator->messages()->first(),
            ];
        }

        return ['success' => true];
    }
}
