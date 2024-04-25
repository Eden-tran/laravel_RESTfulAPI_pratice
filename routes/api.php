<?php

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\ProductController;
// use App\Http\Controllers\API\CustomerController;


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
Route::group(['namespace' => 'App\Http\Controllers\API'], function () {
    Route::apiResource('customer', CustomerController::class);
    Route::apiResource('invoice', InvoiceController::class);
});


// sanctum
// Route::prefix('users')->name('users.')->middleware('auth:sanctum')->group(function () {
// passport
Route::prefix('users')->name('users.')->middleware('auth:api')->group(function () {
    // Matches The "/url/users" URL
    Route::get('/', [UserController::class, 'index'])->name('index');
    Route::get('/{user}', [UserController::class, 'detail'])->name('detail');
    Route::post('/', [UserController::class, 'create'])->name('create');
    Route::put('/{user}', [UserController::class, 'update'])->name('update-put');
    Route::patch('/{user}', [UserController::class, 'update'])->name('update-patch');
    Route::delete('/{user}', [UserController::class, 'delete'])->name('delete');
    // Route::put(uri, callback);
});
Route::apiResource('product', ProductController::class);
Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:api');
Route::get('token', [AuthController::class, 'getToken'])->middleware('auth:sanctum');
Route::post('refresh-token', [AuthController::class, 'refreshToken']);
Route::get('passport-token', function () {
    $user = User::find(1);
    $tokenResult = $user->createToken('auth_api');
    // thiết lập expires
    $token = $tokenResult->token;
    $token->expires_at = Carbon::now()->addMinutes(60);
    // trả về access token
    $accessToken = $tokenResult->accessToken;
    // trả về expires
    $expires = Carbon::parse($token->expires_at)->toDayDateTimeString();
    $response = [
        'access_token' => $accessToken,
        'expires' => $expires,
    ];
    return $response;
});
