<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\WebhookController;
use App\Http\Controllers\Api\TopUpController;

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

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::post('is-email-exist', [App\Http\Controllers\Api\UserController::class, 'isEmailExist']);

//webhook route
Route::post('webhooks', [WebhookController::class, 'update']);

Route::group(['middleware' => ['jwt.verify']], function($router) {
    Route::post('top_ups', [TopUpController::class, 'store']);
    Route::post('transfers', [App\Http\Controllers\Api\TransferController::class, 'store']);
    Route::post('data_plans', [App\Http\Controllers\Api\DataPlanController::class, 'store']);
    Route::get('operator_cards', [App\Http\Controllers\Api\OperatorController::class, 'index']);
    Route::get('payment_methods', [App\Http\Controllers\Api\PaymentMethodController::class, 'index']);
    Route::get('transfer_histories', [App\Http\Controllers\Api\TransferHistoryController::class, 'index']);
    Route::get('transactions', [App\Http\Controllers\Api\TransactionController::class, 'index']);
    Route::get('users', [App\Http\Controllers\Api\UserController::class, 'show']);
    Route::put('users', [App\Http\Controllers\Api\UserController::class, 'update']);
    Route::get('users/{username}', [App\Http\Controllers\Api\UserController::class, 'getByUsername']);
});
