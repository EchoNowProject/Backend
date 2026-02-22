<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\UserNotificationSetting;
use Illuminate\Support\Facades\Auth;

class UserNotificationSettingsController extends Controller
{
    public function getUserNotificationsSettings()
    {
        $user = Auth::user();
        return UserNotificationSetting::findOrFail($user->id)->first();
    }
}
