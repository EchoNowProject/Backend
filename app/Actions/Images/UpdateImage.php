<?php

namespace App\Actions\Images;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UpdateImage extends GetExtension
{

    /**
     * Function que guarda una imagen en la ubicacion deseada y devuelve OK y la extension de la foto
     * @param string $base64
     * @param string $path
     * @return array{extension: string, success: bool}
     */
    public function update(string $base64, string $path): array
    {
        $image = Str::fromBase64($base64);
        $extension = $this->getExtension($base64);

        if (str_contains($base64, ',')) {
            $base64 = explode(',', $base64)[1];
        }

        // Decodificamos la cadena limpia
        $image = base64_decode($base64);

        return [
            'success' => Storage::disk('public')->put($path . '.' . $extension, $image),
            'extension' => $extension,
        ];
    }
}
