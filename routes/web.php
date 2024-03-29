<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/migrate-by-admin', function () {
    Artisan::call('migrate');
});
Route::get('/optimize-clear', function () {
    Artisan::call('optimize:clear');
});

Route::get('/optimize-cache', function () {
    Artisan::call('optimize');
});

Route::get('/db-seed-randfuadmmnf', function () {
    Artisan::call('db:seed');
});


Route::get('/storage-link', function () {
    Artisan::call('storage:link');
});
