<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\AdminSellerController;

// Admin manage Seller 
Route::prefix('/admin/sellers')->name('admin.sellers.')->group(function () {
    Route::get('/', [AdminSellerController::class, 'index'])->name('index'); // Seller Management Page (with tabs: All Sellers & Unverified Sellers)
    Route::get('/{id}/details', [AdminSellerController::class, 'show'])->name('details'); // View detailed profile of a specific seller
    Route::get('/{id}/edit', [AdminSellerController::class, 'edit'])->name('edit'); // Edit seller information (admin only)
    Route::put('/{id}', [AdminSellerController::class, 'update'])->name('update'); // Update seller info after admin edit
    Route::post('/verify/{seller}', [AdminSellerController::class, 'verifySeller'])->name('verify'); // Approve or reject seller verification request
});


Route::get('/', function () {
    return view('welcome');
});

Route::get('/events', function () {
    return view('event.index');
});

Route::get('/users/sellers', function () {
    return view('users.sellers.index');
});

Route::get('/users/buyers', function () {
    return view('users.buyers.index');
});

