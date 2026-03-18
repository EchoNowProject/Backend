<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Controller;
use App\Models\UserNotificationSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserNotificationSettingsController extends Controller
{
    public function getUserNotificationsSettings()
    {
        $user = Auth::user();
        return UserNotificationSetting::findOrFail($user->id);
    }

    public function saveUserNotificationsSettings(Request $request)
    {
        $user = Auth::user();
        $generalSettings = $request->all()['general_settings'];
        $notificationsSettings = $request->all()['notification_settings'];

        /* Guarda el estado de los ajsutes generales del usuario (notificationes) */
        GeneralSettingsController::changeGeneralNotificationState(filter_var($generalSettings['notifications_enable'], FILTER_VALIDATE_BOOL));


        $valuesNotNecessaries = ['user_id', 'created_at', 'updated_at'];

        /* Actualiza todos los campos de notificaciones */
        // El foreach equivale a hacer: show_message_preview => true
        foreach ($notificationsSettings as $key => $value) {
            if (!in_array($key, $valuesNotNecessaries)) {
                UserNotificationSetting::findOrFail($user->id)->update([$key => filter_var($value, FILTER_VALIDATE_BOOL)]);
            }
        }

        return response()->json(AuthController::me(), 200);
    }
}
