<?php

use App\Http\Controllers\API\MidtransController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CitraClientController;
use App\Http\Controllers\CitraPartnerController;
use App\Http\Controllers\CitraServiceController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/




Route::get('/', function () {
    return view('auth.login');
});

//midtrans related
Route::get('midtrans/success', [MidtransController::class, 'success']);
Route::get('midtrans/unfinish', [MidtransController::class, 'unfinish']);
Route::get('midtrans/error', [MidtransController::class, 'error']);




Route::group(['middleware' => ['auth:sanctum', 'verified']], function () {
    Route::name('dashboard.')->prefix('dashboard')->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('index');

        Route::middleware(['admin'])->group(function () {
            Route::resource('service', CitraServiceController::class);
            Route::resource('partner', CitraPartnerController::class);
            Route::resource('client', CitraClientController::class);
            Route::resource('transaction', TransactionController::class);

            Route::resource('user', UserController::class);
        });
    });
});
