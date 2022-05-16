<?php

use App\Http\Controllers\API\CitraClientController;
use App\Http\Controllers\API\CitraPartnerController;
use App\Http\Controllers\API\CitraServiceController;
use App\Http\Controllers\API\QuestionServiceController;
use App\Http\Controllers\API\RoomChatController;
use App\Http\Controllers\API\UserController;
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

//Data'Citra
Route::get('clients', [CitraClientController::class, 'all']);
Route::get('partners', [CitraPartnerController::class, 'all']);
Route::get('services', [CitraServiceController::class, 'all']);
Route::get('room_chat', [RoomChatController::class, 'all']);
Route::get('question', [QuestionServiceController::class, 'all']);


//User
Route::post('register', [UserController::class, 'register']);
Route::post('login', [UserController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('user', [UserController::class, 'fetch']);
    Route::post('user', [UserController::class, 'updateProfile']);
    Route::post('logout', [UserController::class, 'logout']);
});
