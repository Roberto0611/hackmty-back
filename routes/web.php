<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return ['Laravel' => app()->version()];
});

Route::get('/ping', function () {
    return ['pong'];
});

// Deshabilitado para usar solo JWT en api.php
// require __DIR__.'/auth.php';
