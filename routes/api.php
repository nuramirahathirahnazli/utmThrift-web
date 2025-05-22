<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\SellerItemController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/verify-otp', [AuthController::class, 'verifyOtp']);
Route::post('/resend-otp', [AuthController::class, 'sendOtp']);  
Route::post('/login', [AuthController::class, 'login']);

// Protect routes with authentication middleware
// to ensure only authenticated users can access them
Route::middleware('auth:sanctum')->group(function () {

    Route::get('/user', function (Request $request) {
        return response()->json($request->user());
    });

    Route::post('/logout', [AuthController::class, 'logout']); 

    //Routes for profile
    Route::get('/profile', [UserController::class, 'getProfile']);
    Route::put('/profile/update', [UserController::class, 'updateProfile']);

    //Routes for seller manage items
    Route::get('/items/categories', [SellerItemController::class, 'getCategories']); //fetch all item categories
    Route::post('/seller/add-item', [SellerItemController::class, 'store']);
    Route::get('/items/{id}', [SellerItemController::class, 'show']); 
    Route::get('/seller/{id}/items', [SellerItemController::class, 'myItems']);
    Route::post('seller/update-items/{id}', [SellerItemController::class, 'update']);
    Route::delete('/seller/delete-item/{id}', [SellerItemController::class, 'destroy']);

});

//Route::post('/seller/add-item', [SellerItemController::class, 'store']);

   // Routes for Items
   // Route::get('/items', [ItemController::class, 'index']); // Fetch all items
   // Route::post('/items', [ItemController::class, 'store']);
   // Route::get('/items/{id}', [ItemController::class, 'show']); // Fetch a specific item by ID
   // Route::get('/items/categories', [ItemController::class, 'getCategories']); // Fetch all categories
   
//Testing for CORS problem
Route::get('/test-cors', function () {
    return response()->json(['message' => 'CORS is working']);
});
