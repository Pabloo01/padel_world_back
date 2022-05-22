<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\FamilyController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TokenController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ReviewsController;
use App\Http\Controllers\PaymentMethodController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/sanctum/token', TokenController::class);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/users/auth', AuthController::class);
});

Route::middleware('auth:sanctum')->group(function () {

    Route::controller(ProductsController::class)->group(function () {
        Route::get('/products', 'getAllProducts');
        Route::get('/product/{id}', 'getProductById');
        Route::post('/product', 'createProduct');
        Route::post('/product/{id}', 'updateProduct');
        Route::delete('/product/{id}', 'deleteProduct');
    });
    
    Route::controller(FamilyController::class)->group(function(){
        Route::get('/families', 'getAllFamilies');
        Route::get('/family/{id}/products', 'getFamilysProducts');
        Route::post('/family', 'createFamily');
        Route::post('/family/{id}', 'updateFamily');
        Route::delete('/family/{id}', 'deleteFamily');
    });
    
    Route::controller(UserController::class)->group(function(){
        Route::post('/login', 'authenticate')->withoutMiddleware('auth:sanctum');
        Route::post('/register', 'register')->withoutMiddleware('auth:sanctum');
        Route::post('/logout', 'logout');
        Route::post('/user', 'editUser');
    });

    Route::controller(CartController::class)->group(function(){
        Route::get('/cart/items/{id}', 'getAllCartItems');
        Route::get('/cart/{user_id}/{product_id}','checkItemIsInCart');
        Route::get('/orders/{id}','getOrders');
        Route::post('/cart/item', 'addCartItem');
        Route::post('/cart/items', 'updateCartItems');
        Route::post('/cart/buy', 'buyCartItems');
        Route::delete('/cart/item/{id}', 'deleteCartItem');       
    });

    Route::controller(PaymentMethodController::class)->group(function(){
        Route::get('/payment/{id}', 'getPaymentMethod');
        Route::post('/payment', 'addPaymentMethod');
        Route::post('/payment/{id}', 'updatePaymentMethod');
        Route::delete('/payment/{id}', 'deletePaymentMethod');
    });

    Route::controller(ReviewsController::class)->group(function(){
        Route::get('{userOrProduct}/reviews/{id}', 'getReviews');
        Route::post('/review', 'postReview');
        Route::post('/review/{id}', 'editReview');
        Route::delete('/review/{id}', 'deleteReview');
    });
});