<?php

use App\Http\Controllers\OrderController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|

*/
//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});



Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);
Route::middleware('auth:sanctum')->post('/logout', [UserController::class, 'logout']);

Route::middleware(['auth:sanctum'])->group(function () {
//    Route::get('/users/me', [UserController::class, 'me']);
//    Route::put('/users/me', [UserController::class, 'updateProfile']);
//    Route::put('/users/me/change-password', [UserController::class, 'changePassword']);

    //(admin-only)
    Route::get('/users', [UserController::class, 'index']);
    Route::get('/users/{userID}', [UserController::class, 'show']);
    Route::put('/users/{userID}', [UserController::class, 'update']);
    Route::delete('/users/{userID}', [UserController::class, 'destroy']);
    Route::patch('/users/{userID}/change-password', [UserController::class, 'changePassword']);
});


Route::prefix('categories')->group(function () {
    Route::post('/', [CategoryController::class, 'store']); // Add Category
    Route::get('/', [CategoryController::class, 'index']); // Read All Categories
    Route::get('/{categoryID}', [CategoryController::class, 'show']); // Read Single Category
    Route::put('/{categoryID}', [CategoryController::class, 'update']); // Update Category
    Route::delete('/{categoryID}', [CategoryController::class, 'destroy']); // Delete Category
});

Route::prefix('products')->group(function () {
    Route::post('/', [ProductController::class, 'store']); // Add Product
    Route::get('/', [ProductController::class, 'index']); // Read All Products
    Route::get('/{product_id}', [ProductController::class, 'show']); // Read Single Product
    Route::put('/{product_id}', [ProductController::class, 'update']); // Update Product
    Route::delete('/{product_id}', [ProductController::class, 'destroy']); // Delete Product
    Route::get('/type/{type}', [ProductController::class, 'getProductsByType']); // Read All Products by Type
});


Route::prefix('orders')->middleware('auth:sanctum')->group(function () {
    Route::post('/', [OrderController::class, 'store']);
    Route::get('/', [OrderController::class, 'index']);
    Route::patch('/{orderID}', [OrderController::class, 'edit']);
    Route::put('/{orderID}', [OrderController::class, 'update']);
    Route::get('/{orderID}', [OrderController::class, 'show']);
    Route::patch('/{order}/payment', [OrderController::class, 'payment']);
    Route::get('/type/{type}', [OrderController::class, 'getOrdersByType']);
});








