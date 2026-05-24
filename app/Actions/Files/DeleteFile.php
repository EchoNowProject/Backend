<?php

namespace App\Actions\Files;

use Illuminate\Support\Facades\Storage;

class DeleteFile
{

    /**
     * Function que comprueba que existe la ruta y elimina el fichero
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
