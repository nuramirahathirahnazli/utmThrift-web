<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\AdminSellerController;
use App\Http\Controllers\AdminEventController;

// Admin manage Seller 
Route::prefix('/admin/sellers')->name('admin.sellers.')->group(function () {
    Route::get('/', [AdminSellerController::class, 'index'])->name('index'); // Seller Management Page (with tabs: All Sellers & Unverified Sellers)
    Route::get('/{id}/details', [AdminSellerController::class, 'show'])->name('show'); // View detailed profile of a specific seller
    Route::get('/{id}/edit', [AdminSellerController::class, 'edit'])->name('edit'); // Edit seller information (admin only)
    Route::put('/{id}', [AdminSellerController::class, 'update'])->name('update'); // Update seller info after admin edit
    Route::post('/verify/{seller}', [AdminSellerController::class, 'verifySeller'])->name('verify'); // Approve or reject seller verification request
});

//Admin manage Event
Route::prefix('/admin/events')->name('admin.events.')->group(function () {
    Route::get('/', [AdminEventController::class, 'index'])->name('index'); // Event Management Page
    Route::get('/create', [AdminEventController::class, 'create'])->name('create'); // Create Event Page
    Route::post('/', [AdminEventController::class, 'store'])->name('store'); // Store Event
    Route::get('/{event}/edit', [AdminEventController::class, 'edit'])->name('edit'); // Edit Event Page
    Route::put('/{event}', [AdminEventController::class, 'update'])->name('update'); // Update Event
    Route::get('/{event}', [AdminEventController::class, 'show'])->name('show');
    Route::delete('/{event}', [AdminEventController::class, 'destroy'])->name('destroy'); // Delete Event
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

