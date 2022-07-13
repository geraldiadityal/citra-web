<?php

use App\Http\Controllers\API\ChatsController;
use App\Http\Controllers\API\CitraClientController;
use App\Http\Controllers\API\CitraPartnerController;
use App\Http\Controllers\API\CitraServiceController;
use App\Http\Controllers\API\MidtransController;
use App\Http\Controllers\API\QuestionServiceController;
use App\Http\Controllers\API\RoomChatController;
use App\Http\Controllers\API\SessionChatController;
use App\Http\Controllers\API\TransactionController;
use App\Http\Controllers\API\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Broadcast;
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



Broadcast::routes(['middleware' => ['auth:sanctum', 'partner']]);

//for Midtrans
Route::post('midtrans/callback', [MidtransController::class, 'callback']);

//Data'Citra
Route::get('clients', [CitraClientController::class, 'all']);
Route::get('partners', [CitraPartnerController::class, 'all']);
Route::get('services', [CitraServiceController::class, 'all']);
Route::get('question', [QuestionServiceController::class, 'all']);

//Login and Register User
Route::post('register', [UserController::class, 'register']);
Route::post('login', [UserController::class, 'login']);


//for use need token
Route::middleware('auth:sanctum')->group(function () {
    Route::get('user', [UserController::class, 'fetch']);
    Route::post('user', [UserController::class, 'updateProfile']);
    Route::post('logout', [UserController::class, 'logout']);


    Route::get('transaction', [TransactionController::class, 'all']);
    Route::post('transaction/{id}', [TransactionController::class, 'update']);

    Route::post('checkout', [TransactionController::class, 'checkout']);

    Route::get('session', [SessionChatController::class, 'all']);


    //for chat pusher
    Route::post('send/{sessionChats}', [ChatsController::class, 'send']);
    Route::get('chats', [ChatsController::class, 'fetch']);
});
