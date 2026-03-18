<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Controller;
use App\Models\UserPrivacySetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserPrivacityController extends Controller
{

    private $user;

    public function __construct()
    {
        $this->user = Auth::user();
    }

    // Funcion que retorna los ajustes de privacidad del usuario logueado
    public function getUserPrivacitySettings()
    {
        return UserPrivacySetting::findOrFail($this->user->id);
    }

    // Funcion para actualizar las opciones de propiedad de un usuario
    public function saveUserPrivacitySettings(Request $request)
    {
        $privacitySettings = $request->all()['privacity_settings'];

        $valuesNotNecessaries = ['user_id', 'created_at', 'updated_at'];

        /* Actualiza todos los campos de privacidad */
        // El foreach equivale a hacer: show_message_preview => true
        foreach ($privacitySettings as $key => $value) {
            if (!in_array($key, $valuesNotNecessaries)) {
                UserPrivacySetting::findOrFail($this->user->id)->update([$key => filter_var($value, FILTER_VALIDATE_BOOL)]);
            }
        }

        return response()->json(AuthController::me(), 200);
    }
}
