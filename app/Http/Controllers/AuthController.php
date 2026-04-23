<?php

namespace App\Http\Controllers;

use App\Actions\Fortify\ResetUserPassword;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

//
// ? Para mas informacion de la Autenticacion consultar en
// ? https://jwt-auth.readthedocs.io/en/develop/
//

class AuthController extends Controller
{
    /**
     * Handle an authentication attempt.
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

        if (! $token = Auth::attempt($credenciales)) {
            return response()->json('Usuario o contraseña incorrectos', 401);
        }


        return $this->respondWithToken($token);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public static function me()
    {
        $user = Auth::user();
        return response()->json($user->load('generalSettings'));
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        Auth::logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(Auth::refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        $user = Auth::user();

        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            //'expires_in' => Auth::factory()->getTTL() * 60,
            'user' => $user->load('generalSettings'),
        ]);
    }

    /**
     * Actualiza la contraseña de un usuario logueado
     * @param $actualPassword
     * @param $newPassword
     * @param $confirmPassword
     * @return void
     */
    public function updatePassword(Request $request): JsonResponse
    {
        $user = Auth::user();
        $resetUser = new ResetUserPassword();
        $passwordChanged = $resetUser->reset($user, $request->all()['data']);

        return response()->json($passwordChanged['message'], $passwordChanged['httpStatus']);
    }
}
