<?php

use App\Http\Controllers\ContactUsController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ShippingProductController;
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
Route::post('/reset-password', [UserController::class, 'resetPassword']);
Route::middleware('auth:sanctum')->post('/logout', [UserController::class, 'logout']);
Route::middleware('auth:sanctum')->post('/contact-us', [ContactUsController::class, 'submit']);



Route::prefix('users')->middleware(['auth:sanctum'])->group(function () {
    // Normal user routes
    Route::get('/user', [UserController::class, 'show']);
    Route::patch('/update-account', [UserController::class, 'updateAccountInfo']);
    Route::patch('/change-password', [UserController::class, 'changePassword']);

    // Admin-only routes
    Route::middleware(['admin'])->group(function () {
        Route::get('/', [UserController::class, 'index']);
        Route::get('/usertype/{usertype}', [UserController::class, 'getUsersByUsertype']);
        Route::delete('/{user_id}', [UserController::class, 'destroy']);
    });
});



Route::prefix('categories')->middleware(['auth:sanctum'])->group(function () {
//    normal user

//    admin-only routes
    Route::middleware(['admin'])->group(function (){
        Route::post('/', [CategoryController::class, 'store']); // Add Category
        Route::get('/', [CategoryController::class, 'index']); // Read All Categories
        Route::get('/{category_id}', [CategoryController::class, 'show']); // Read Single Category
        Route::put('/{category_id}', [CategoryController::class, 'update']); // Update Category
        Route::delete('/{category_id}', [CategoryController::class, 'destroy']); // Delete Category
    });
});

Route::prefix('products')->middleware(['auth:sanctum'])->group(function () {
    // normal user
    Route::get('/', [ProductController::class, 'index']); // Read All Products
    Route::get('/{product_id}', [ProductController::class, 'show']); // Read Single Product
    Route::get('/type/{type}', [ProductController::class, 'getProductsByType']); // Read All Products by Type

    // admin-only routes
    Route::middleware(['admin'])->group(function (){
        Route::post('/', [ProductController::class, 'store']); // Add Product
        Route::post('/{product_id}', [ProductController::class, 'update']); // Update Product
        Route::delete('/{product_id}', [ProductController::class, 'destroy']); // Delete Product
    });

});

Route::prefix('shipping_products_inquiry')->middleware(['auth:sanctum'])->group(function () {
    // normal user
    Route::post('/', [ShippingProductController::class, 'store']); // Add Shipping Product

    // admin-only routes
    Route::middleware(['admin'])->group(function (){
        Route::get('/', [ShippingProductController::class, 'index']); // Read All Shipping Products
        Route::get('/{shipping_product_id}', [ShippingProductController::class, 'show']); // Read Single Shipping Product
        Route::post('/{shipping_product_id}', [ShippingProductController::class, 'update']); // Update Shipping Product
        Route::delete('/{shipping_product_id}', [ShippingProductController::class, 'destroy']); // Delete Shipping Product
    });

});


Route::post('send-dummy-data', [OrderController::class, 'sendDummyData']);

Route::prefix('orders')->middleware(['auth:sanctum'])->group(function () {
    // normal user
    Route::post('/', [OrderController::class, 'store']);
    Route::patch('/{order_id}/payment', [OrderController::class, 'payment']);
    Route::get('/history', [OrderController::class, 'getUserOrderHistory']);


    // admin-only routes
    Route::middleware(['admin'])->group(function () {
        Route::get('/', [OrderController::class, 'index']);
        Route::patch('/{order_id}', [OrderController::class, 'edit']);
        Route::put('/{order_id}', [OrderController::class, 'update']);
        Route::get('/{order_id}', [OrderController::class, 'show']);
        Route::patch('/{order_id}/complete', [OrderController::class, 'completeOrder']);
        Route::get('/type/{type}/status/{status}', [OrderController::class, 'getOrdersByTypeAndStatus']);
    });
});



Route::prefix('faqs')->middleware(['auth:sanctum'])->group(function () {
    // normal user
    Route::get('/', [FaqController::class, 'index']); // Read All FAQs


    // admin-only routes
    Route::middleware(['admin'])->group(function () {
        Route::post('/', [FaqController::class, 'store']); // Add FAQ
        Route::get('/{faq_id}', [FaqController::class, 'show']); // Read Single FAQ
        Route::put('/{faq_id}', [FaqController::class, 'update']); // Update FAQ
        Route::delete('/{faq_id}', [FaqController::class, 'destroy']); // Delete FAQ
    });
    });







