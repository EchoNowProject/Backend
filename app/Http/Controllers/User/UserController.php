<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validateFields($request); // devuelve un error 422 si falla algo
        DB::beginTransaction();
        $user = new User();
        $user->fill($request->all());
        $user->password = Hash::make($request->all()['password']);

        $user->status = 1; // 👈  Online
        $user->plan = 1; // 👈  Basic
        try {
            $user->save();
        } catch (Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
        DB::commit();
        return response()->json('Usuario creado con exito', 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::with('status', 'plan')->find($id);
        if (isset($user)) {
            return response()->json($user, 200);
        }
        return response()->json("No se ha encontrado el usuario", 500);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        DB::beginTransaction();
        User::findOrFail($id)->delete();
        DB::commit();
        return response()->json("Se ha eliminado corerctamente el usuario", 200);
    }

    /* Validaciones de datos*/
    private function validateFields(Request $request)
    {
        return Validator::make($request->all(), [
            'password' => 'required',
            'username' => 'required|max:50',
            'email' => 'required',
        ], [
            'password' => "No puedes registrarte sin contraseña",
            'username' => "No puedes registrarte sin nombre de usuario",
            'email' => "No puedes registrarte sin correo electronico",
        ])->validate();
    }
}
