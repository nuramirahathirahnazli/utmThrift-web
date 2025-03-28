<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ItemController;

Route::get('/', [ItemController::class, 'index']);

Route::get('/events', function () {
    return view('event.index');
});

Route::get('/users/sellers', function () {
    return view('users.sellers.index');
});

Route::get('/users/buyers', function () {
    return view('users.buyers.index');
});

