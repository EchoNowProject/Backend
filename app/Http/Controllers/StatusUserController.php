<?php

namespace App\Http\Controllers;

use App\Models\StatusUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StatusUserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $idStatusUser = Auth::user()->status;

        return StatusUser::whereNotIn('id', [$idStatusUser, 6, 3])->get();
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return StatusUser::findOrFail($id);
    }

    /**
     * Display the specified resource.
     */
    public function getMyStatus()
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(
                'Usuario no autenticado',
                401
            );
        }

        return response()->json(
            $user->statusUser,
            200
        );
    }

    public function setNewStatus(int $idStatus)
    {

        if ($idStatus <= 6) {

            $user = Auth::user();

            if (!$user) {
                return response()->json(
                    'Usuario no autenticado',
                    401
                );
            }

            $user->status = $idStatus;
            $user->save();
        }
    }
}
