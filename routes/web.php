<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return ['Laravel' => app()->version()];
});

Route::get('/ping', function () {
    return ['pong'];
});

// require __DIR__.'/auth.php';
