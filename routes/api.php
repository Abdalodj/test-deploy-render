<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PortfolioController;
use App\Http\Controllers\TemplateController;




Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);
Route::post('/password-forgot', [AuthController::class, 'forgotPassword']);
Route::post('/password-reset', [AuthController::class, 'resetPassword']);
Route::middleware('auth:sanctum')->post('/profile-picture', [AuthController::class, 'updateProfilePicture']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return response()->json($request->user());
    });

    Route::post('/portfolios', [PortfolioController::class, 'store']);
    Route::get('/portfolios/{portfolio}', [PortfolioController::class, 'show']);
    Route::put('/portfolios/{portfolio}/order', [PortfolioController::class, 'updateSectionOrder']);
    Route::put('/portfolios/{portfolio}', [PortfolioController::class, 'update']);
    Route::delete('/portfolios/{portfolio}', [PortfolioController::class, 'destroy']);



    Route::middleware('auth:sanctum')->post('/templates', [TemplateController::class, 'store']);
    Route::middleware('auth:sanctum')->get('/templates', [TemplateController::class, 'index']);





});
