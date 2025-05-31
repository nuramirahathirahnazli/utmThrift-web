<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\SellerItemController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\ItemFavouriteController;

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

    ///  ---- User Buyer Routes ---- ///
    // Route for homescreen user
    Route::get('/events', [EventController::class, 'index']); // Latest or all events
    Route::get('/events/{id}', [EventController::class, 'show']); // Single event details
    Route::get('/items', [ItemController::class, 'listItems']);
    Route::get('/buyer/items/{id}', [ItemController::class, 'show']);

    // Routes for user favourite items
    Route::post('/item/{id}/toggle-favourite', [ItemFavouriteController::class, 'toggleFavourite']);
    Route::get('/item/favourites', [ItemFavouriteController::class, 'getUserFavourites']);
   
    ///  ---- Seller Routes ---- ///
    //Routes for seller manage items
    Route::get('/items/categories', [SellerItemController::class, 'getCategories']); //fetch all item categories
    Route::post('/seller/add-item', [SellerItemController::class, 'store']);
    Route::get('/items/{id}', [SellerItemController::class, 'show']); 
    Route::get('/seller/{id}/items', [SellerItemController::class, 'myItems']);
    Route::post('seller/update-items/{id}', [SellerItemController::class, 'update']);
    Route::delete('/seller/delete-item/{id}', [SellerItemController::class, 'destroy']);

    // Explore page routes with filters (example: /api/items?search=shirt&category_id=2&min_price=10&max_price=50&condition=new)
    // This is handled by ItemController@listItems above with query parameters
});


//Testing for CORS problem
Route::get('/test-cors', function () {
    return response()->json(['message' => 'CORS is working']);
});
