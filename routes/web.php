<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RedirectPaymentController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\Admin\AuthController;

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

Route::get('payments_finish', [RedirectPaymentController::class, 'finish']);

//route group
Route::group(['prefix' => 'admin'], function () {
    //login
    Route::view('login', 'login')->name('admin.auth.index');
    Route::post('login', [AuthController::class, 'login'])->name('admin.auth.login');
    //logout
    Route::get('logout', [AuthController::class, 'logout'])->name('admin.auth.logout');

    //with middleware
    Route::group(['middleware' => 'auth:web'], function () {
        Route::get('dashboard', function () {
            return view('dashboard');
        })->name('admin.dashboard');
        Route::get('transaction', [TransactionController::class, 'index'])
        ->name('admin.transaction.index');
    });
});
