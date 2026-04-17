<?php

namespace App\Actions\Images;

use Illuminate\Support\Facades\Storage;

class DeleteImage
{

    /**
     * Function que comprueba que existe la ruta y elimina la imagen
     * @param string $path
     * @return bool
     */
    public function delete(string $path): bool
    {
        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
            return true;
        }

        return false;
    }
}
