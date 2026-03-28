<?php

namespace App\Actions\Images;

class GetExtension
{
    /**
     * Function que devuelve el mimetype de un archivo guardado
     * @param string $path
     * @return string extension
     */
    public function getExtension(string $base64): string
    {

        list($meta, $data) = explode(',', $base64);
        preg_match('/data:(.*);base64/', $meta, $matches);

        $mime = $matches[1]; // image/png
        $extension = explode('/', $mime)[1];

        return $extension ?? null;
    }
}
