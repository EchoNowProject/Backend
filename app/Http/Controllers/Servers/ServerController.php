<?php

namespace App\Http\Controllers\Servers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreServerRequest;
use App\Models\Server;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ServerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Server::where('owner_id', Auth::id())->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreServerRequest $request)
    {
        DB::beginTransaction();

        do {
            $code = 'echonow:' . Str::random(25);
        } while (Server::where('invitation_code', $code)->exists());

        try {
            $server = Server::create([
                'name'        => $request->name,
                'description' => $request->description ?? null,
                'avatar_img'  => $request->avatar_img ?? null,
                'owner_id'    => Auth::id(),
                'invitation_code' => 'echonow:' . Str::random(),
                'type_server' => $request->type_server,
            ]);
            DB::commit();
            return response()->json([
                'message' => 'Servidor creado correctamente',
                'server' => $server,
            ], 201);
        } catch (Exception $error) {
            DB::rollBack();
            return response()->json($error->getMessage(), 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
        //
    }
}
