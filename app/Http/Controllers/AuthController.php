<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Handle an authentication attempt.
     * ! Falta pasar el token
     */
    public function login(Request $request)
    {

        $credenciales = Validator::make($request->all(), [
            'email' => ['required', 'email'],
            'password' => ['required'],
        ], [
            'email.required' => "Falta el campo email",
            'password.required' => "Falta el campo contraseña"
        ])->validate();

        if (Auth::attempt($credenciales)) {
            //$request->session()->regenerate();
            return response()->json("Sesion iniciada con exito", 200);
        }

        return response()->json("El usuario y la contraseña no coinciden", 401);
    }
}
