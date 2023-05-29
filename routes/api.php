<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Actions\Fortify\CreateNewUser;

use App\Http\Controllers\Api\AuthController;
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







Route::post('login', [AuthController::class, 'login']);
Route::post('/register', function (Request $request, CreateNewUser $creator) {
    $user = $creator->create($request->all());
     $message = 'Datos registrados exitosamente';
    return response()->json(['user' => $user, 'message' => $message], 201);
});



Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    // Otras rutas que requieren autenticación y verificación
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('user', [AuthController::class, 'user']);
    Route::get('/users', [AuthController::class, 'getUsers']);
    Route::post('update-password', [AuthController::class, 'updatePassword']);
    Route::post('forgot-password', [AuthController::class, 'forgotPassword']);
    Route::post('reset-password', [AuthController::class, 'resetPassword']);
    Route::post('update-profile', [AuthController::class, 'updateProfile']);
});
