<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Actions\Fortify\CreateNewUser;

use App\Http\Controllers\LoginController;
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







Route::post('login', [LoginController::class, 'login']);
Route::post('/register', function (Request $request, CreateNewUser $creator) {
    $user = $creator->create($request->all());
    return response()->json(['user' => $user], 201);
});



Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    // Otras rutas que requieren autenticación y verificación
    Route::post('logout', [LoginController::class, 'logout']);
    Route::post('user', [LoginController::class, 'user']);
    Route::post('update-password', [LoginController::class, 'updatePassword']);
    Route::post('forgot-password', [LoginController::class, 'forgotPassword']);
    Route::post('reset-password', [LoginController::class, 'resetPassword']);
    Route::post('update-profile', [LoginController::class, 'updateProfile']);
});
