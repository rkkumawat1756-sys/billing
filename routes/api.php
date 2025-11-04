<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TestApiController;
use App\Http\Controllers\api\AuthController;
use App\Http\Controllers\api\ApiController;

Route::get('/test', [TestApiController::class, 'index']);

Route::post('register', [AuthController::class, 'register']);
Route::post('/send-otp', [AuthController::class, 'sendOtp']);
Route::post('/verify-otp', [AuthController::class, 'verifyOtp']);
Route::post('/getCategory', [ApiController::class, 'getCategory']);
Route::post('/getProduct', [ApiController::class, 'getProduct']);
Route::post('/getProductDetail', [ApiController::class, 'getProductDetail']);
Route::post('/editProfile', [ApiController::class, 'editProfile']);
Route::post('/addCart', [ApiController::class, 'addCart']);
Route::post('/addToCart', [ApiController::class, 'addToCart']);
Route::post('/cartRemove', [ApiController::class, 'cartRemove']);
Route::post('/showCart/{id}', [ApiController::class, 'showCart']);



// Route::middleware('auth:sanctum')->group(function () {
//  Route::post('/getProductDetail', [ApiController::class, 'getProductDetail']);
// });