<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ResetPasswordController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/send-otp',[ResetPasswordController::class,'SendOTPCode']);
Route::post('/verify-otp',[ResetPasswordController::class,'VerifyOTP']);


Route::middleware('auth:sanctum')->group(function () {  
    Route::post('/reset-password',[ResetPasswordController::class,'ResetPassword']);
    Route::get('/user', [ProfileController::class, 'userProfile']); 
    Route::post('/profile/update', [ProfileController::class,'update']);  
    Route::post('/logout', [AuthController::class, 'logout']);

    // category 
    
    Route::post('/category', [CategoryController::class, 'store']);

    // product
    Route::post('/product', [ProductController::class, 'store']);
});

Route::get('/category', [CategoryController::class, 'index']);
Route::get('/product', [ProductController::class, 'index']);
Route::get('/product/{product}', [ProductController::class, 'show']);




