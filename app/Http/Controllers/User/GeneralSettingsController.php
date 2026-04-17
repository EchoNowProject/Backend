<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\UserSetting;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class GeneralSettingsController extends Controller
{
    public static function changeGeneralNotificationState(bool $activo): bool
    {

        $user = Auth::user();

        DB::beginTransaction();
        try {
            UserSetting::findOrFail($user->id)->updateOrFail(['notifications_enable' => $activo]);
            DB::commit();
            return true;
        } catch (Exception $error) {
            Log::debug($error);
            DB::rollBack();
        }

        return false;
    }
}
