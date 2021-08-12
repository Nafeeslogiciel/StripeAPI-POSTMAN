<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\CustomerController;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();

});

Route::post('/register',[RegisterController::class,'register']);
Route::post('/login',[RegisterController::class,'login']);


Route::middleware('auth:api')->group( function () {
    
    Route::get('/plans', [PlanController::class,'index']);
    Route::get('/plan/{id}', [PlanController::class,'show']);
    Route::post('/subscription/{id}', [SubscriptionController::class,'create']);

    //Routes for create Plan
    Route::post('createplan', [SubscriptionController::class,'storePlan']);

    //Routes for Customer
    Route::post('/createcustomer', [CustomerController::class,'customer']);
    Route::get('/allcustomer', [CustomerController::class,'showcustomer']);

});
