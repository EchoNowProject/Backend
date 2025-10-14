<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum'); /* Para peticiones que tenga el usuario autenticado */

Route::get('/hello', function () {
    return "hello world!";
});
