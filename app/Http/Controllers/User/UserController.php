<?php

namespace App\Http\Controllers\User;

use App\Actions\Fortify\CreateNewUser;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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

        $data = $request->all();
        DB::beginTransaction();
        $fortify = new CreateNewUser();
        $fortify->create($data);
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
    public function update(StoreUserRequest $request)
    {
        try {
            DB::beginTransaction();
            $user = Auth::user();
            $user->update($request->validated());

            DB::commit();
            return response()->json($user, 200);
        } catch (Exception $error) {
            DB::rollBack();
            return response()->json(['user' => Auth::user(), 'error' => $error->getMessage()], 500);
        }
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
