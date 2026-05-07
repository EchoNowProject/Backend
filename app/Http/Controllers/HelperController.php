<?php

namespace App\Http\Controllers;

use App\Actions\Files\GetFile;
use Illuminate\Http\Request;

class HelperController extends Controller
{

    /**
     * Retorna un fichero en base64 a traves de una peticion API
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function downloadFileByPath(Request $request)
    {
        $path = $request->query('internalPath');

        $action = new GetFile();
        $base64 = $action->get($path);

        if ($base64 !== null) {
            return response()->json($base64, 200);
        }
        return response()->json('El fichero no se ha encontrado', 404);

    }
}
