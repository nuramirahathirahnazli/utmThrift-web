<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\AdminSellerController;
use App\Http\Controllers\AdminEventController;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;

// Admin manage Seller 
Route::prefix('/admin/sellers')->name('admin.sellers.')->group(function () {
    Route::get('/', [AdminSellerController::class, 'index'])->name('index');
    Route::get('/{id}/details', [AdminSellerController::class, 'show'])->name('show');
    Route::get('/{id}/edit', [AdminSellerController::class, 'edit'])->name('edit');
    Route::put('/{id}', [AdminSellerController::class, 'update'])->name('update');
    Route::post('/verify/{seller}', [AdminSellerController::class, 'verifySeller'])->name('verify');
});

// Admin manage Event
Route::prefix('/admin/events')->name('admin.events.')->group(function () {
    Route::get('/', [AdminEventController::class, 'index'])->name('index');
    Route::get('/create', [AdminEventController::class, 'create'])->name('create');
    Route::post('/', [AdminEventController::class, 'store'])->name('store');
    Route::get('/{event}/edit', [AdminEventController::class, 'edit'])->name('edit');
    Route::put('/{event}', [AdminEventController::class, 'update'])->name('update');
    Route::get('/{event}', [AdminEventController::class, 'show'])->name('show');
    Route::delete('/{event}', [AdminEventController::class, 'destroy'])->name('destroy');
});

// Route for item images
// Route::get('/item-image/{filename}', function ($filename) {
//     $path = 'items/' . $filename;

//     if (!Storage::disk('public')->exists($path)) {
//         abort(404);
//     }

//     $file = Storage::disk('public')->get($path);
//     $type = Storage::disk('public')->mimeType($path);

//     return Response::make($file, 200)
//         ->header('Content-Type', $type)
//         ->header('Access-Control-Allow-Origin', '*'); // Allow CORS
// });

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
