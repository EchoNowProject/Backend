<?php

namespace App\Actions\Files;

use Illuminate\Support\Facades\Storage;

class GetFile
{

    /**
     * Funcion que obtiene la imagen como base64 a partir de una ruta del sistema
     * @param string $path
     * @return string|null
     */
    public function get(string $path): string|null
    {
        if (!$path) {
            return null;
        }

        if (!Storage::disk('public')->exists($path)) {
            return null;
        }

        $file = Storage::disk('public')->get($path);

        return base64_encode($file);
    }
}
