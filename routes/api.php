<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\SellerItemController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\ItemFavouriteController;
use App\Http\Controllers\ItemCartController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ToyyibPayController;
use App\Http\Controllers\ReviewRatingController;

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
    Route::get('/items/all-favourited', [ItemFavouriteController::class, 'getAllFavouritedItems']);

    //Routes for user cart
    Route::post('/cart/add', [ItemCartController::class, 'addToCart']);
    Route::get('/cart/{id}', [ItemCartController::class, 'getCartItems']);
    Route::delete('/cart/remove/{itemId}', [ItemCartController::class, 'removeItem']);

    // Routes for user messages
    Route::get('/messages', [MessageController::class, 'index']);
    Route::post('/messages', [MessageController::class, 'store']);
    Route::get('/buyer/messages/{buyer_id}', [MessageController::class, 'getUserMessages']);
    Route::get('/seller/messages/{seller_id}', [MessageController::class, 'getSellerMessages']);
    Route::get('/messages/unread-count', [MessageController::class, 'getUnreadCount']);
    Route::get('/messages/chat-list', [MessageController::class, 'getChatList']);
    Route::post('/messages/mark-as-read', [MessageController::class, 'markAsRead']);

    // Routes for user orders checkout
    Route::post('/checkout/meetup', [OrderController::class, 'checkoutMeetUp']);
    Route::post('/orders/create', [OrderController::class, 'create']);
    Route::post('/orders/{orderId}/confirm', [OrderController::class, 'confirmOrder'])->middleware('auth:sanctum'); 
    Route::get('/orders/buyer', [OrderController::class, 'getBuyerOrders']);
    Route::post('/orders/{id}/manual-confirm', [OrderController::class, 'manualConfirm']); //for payment method = online banking

    // Routes for payment
    Route::post('/create-bill', [ToyyibPayController::class, 'createBill']);
    
    //Routes for review/rating to seller
    Route::post('/reviews', [ReviewRatingController::class, 'store']);             // Submit review
    Route::get('/reviews/seller/{sellerId}', [ReviewRatingController::class, 'sellerReviews']); // Get all reviews for a seller
    Route::get('/rating/seller/{sellerId}', [ReviewRatingController::class, 'averageRating']); // Get average rating for a seller


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
