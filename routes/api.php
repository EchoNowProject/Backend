<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum'); /* Para peticiones que tenga el usuario autenticado */

// ✅ Cargar automáticamente todos los archivos PHP dentro de routes/**
$directory = base_path('routes');

// Recorre todas las subcarpetas
$iterator = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($directory)
);

foreach ($iterator as $file) {
    // Saltar este mismo archivo (api.php)
    if ($file->isFile() && $file->getExtension() === 'php' && $file->getFilename() !== 'api.php') {
        require $file->getPathname();
    }
}
