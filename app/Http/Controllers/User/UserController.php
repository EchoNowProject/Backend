<?php

namespace App\Http\Controllers\User;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Images\DeleteImage;
use App\Actions\Images\GetExtension;
use App\Actions\Images\GetMimeType;
use App\Actions\Images\UpdateImage;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UserController extends Controller
{

    const IMAGEUSERPATH = "/users/";
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
        $user = User::with('statusUser', 'plan')->find($id);
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
        $user = Auth::user();

        try {
            if (
                !ctype_digit($request->all()['telephone_number'])
                || strlen($request->all()['telephone_number']) > 15
            ) {
                throw new Exception('El campo número de teléfono no es válido');
            }

            DB::beginTransaction();
            $user->update([
                'username' => $request->validated()['username'],
                'display_name' => $request->all()['display_name'] ?? null,
                'biography' => $request->all()['biography'] ?? null,
                'telephone_number' => $request->all()['telephone_number'] ?? null,
                'prefix_telephone_number' => $request->all()['prefix_telephone_number'] ?? null,
            ]);
            DB::commit();

            return response()->json($user->load('generalSettings'), 200);
        } catch (Exception $error) {
            DB::rollBack();
            $user = $user->load('generalSettings');
            return response()->json(['user' => $user, 'error' => $error->getMessage()], 500);
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

    /**
     * Funcion para eliminar la foto de perfil del usuario logueado
     * @param Request $request->avatar_img
     */
    public function deleteUserImage(Request $request)
    {
        $user = Auth::user();
        $path = self::IMAGEUSERPATH . $request->avatar_img;

        $deleteImage = new DeleteImage();
        if ($deleteImage->delete($path)) {
            DB::beginTransaction();
            $user->update(['avatar_img' => null]);
            DB::commit();

            return response()->json($user->load('generalSettings'), 200);
        }

        return response()->json("No se ha podido eliminar el recurso", 500);
    }

    /**
     * Funcion para actualizar/aniadir la foto de perfil del usuario logueado
     * @param Request FileData
     */
    public function updateUserImage(Request $request)
    {
        $user = Auth::user();
        $base64 = $request->base64;

        /* Si un usuario ya tiene foto y la quiere cambiar */
        if ($user->avatar_img) {
            $deleteImage = new DeleteImage();
            $deleteImage->delete(self::IMAGEUSERPATH . $user->avatar_img);
        }

        $newName = Str::random(20);

        // Subir la imagen
        $updateImage = new UpdateImage();
        $imageUpload = $updateImage->update($base64, self::IMAGEUSERPATH . $newName);

        DB::beginTransaction();
        $user->update(['avatar_img' => $newName . '.' . $imageUpload['extension']]); // Saca la extension de la foto
        DB::commit();

        if ($imageUpload['success']) {
            return response()->json([
                'avatar_img' => $user->avatar_img,
                'fileImage' => $user->fileAvatarImage
            ], 200);
        }

        return response()->json('Error al añadir la nueva imagen', 500);
    }
}
