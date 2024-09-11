<?php

use App\Http\Controllers\ApiController;
use App\Http\Controllers\PostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('posts',[PostController::class,'index']);
Route::post('posts',[PostController::class,'store']);
Route::get('posts/{id}',[PostController::class,'show']);
Route::put('posts/{post}',[PostController::class,'update']);
Route::delete('posts/{post}',[PostController::class,'destroy']);

Route::get('configs',[ApiController::class,'configs']);
Route::post('register',[ApiController::class,'register']);
Route::post('update',[ApiController::class,'update_user']);
Route::post('delete',[ApiController::class,'delete_user']);
Route::post('get-login-otp',[ApiController::class,'getLoginOtp']);
Route::post('verify-login-otp',[ApiController::class,'VerifyLoginOtp']);
Route::post('/apply-coupon',[ApiController::class,'applyCoupon']);
Route::post('user/delete-coupon',[ApiController::class,'DeleteCoupon']);
Route::get('user/profile',[ApiController::class,'get_profile']);
Route::any('user/shipping-address',[ApiController::class,'shipping_address']);
Route::put('user/shipping-address/{id}',[ApiController::class,'update_shipping_address']);

// material details:
// Route::any('get_material_details',[ApiController::class,'get_material_details']);
Route::options('/details', [ApiController::class, 'get_material_details']);
// products related:
Route::get('products-by-category/{category_id}',[ApiController::class,'get_products_by_category_id']);
Route::get('category/all',[ApiController::class,'get_all_category']);
Route::get('all-brand',[ApiController::class,'get_all_brand']);




