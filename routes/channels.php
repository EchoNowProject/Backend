<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('test-channel', function ($user) {
    return true; // permite acceso (para pruebas)
});

Broadcast::channel('friend-request.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});
