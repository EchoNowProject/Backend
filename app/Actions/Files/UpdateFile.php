<?php

namespace App\Actions\Files;

use Illuminate\Support\Facades\Storage;

class UpdateFile
{

    /**
     * Funcion que guarda un fichero en la ruta indicada
     * @param string $base64
     * @param string $path
     * @param string $fileName
     * @return array{file_name: string, path: string, success: bool}
     */
    public function update(string $base64, string $path, string $fileName): array
    {
        $path .= $fileName;

        if (str_contains($base64, ',')) {
            $base64 = explode(',', $base64)[1];
        }

        // Decodificar
        $image = base64_decode($base64);

        // Guardar
        $saved = Storage::disk('public')->put($path, $image);

        return [
            'success' => $saved,
            'path' => $path,
            'file_name' => $fileName,
        ];
    }
}
