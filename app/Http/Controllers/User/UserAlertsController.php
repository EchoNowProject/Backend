<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\UserAlert;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserAlertsController extends Controller
{

    public function getAlertsByUser()
    {
        Auth::user();

        $alerts = UserAlert::where('target_user_id', Auth::id())->get();

        return response()->json($alerts, 200);
    }
}
