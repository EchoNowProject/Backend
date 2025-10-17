<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return "hola controller";
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        $user = User::fill($request->all());
        // ! Terminar
        DB::commit();
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
}
