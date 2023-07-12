<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Actions\Fortify\CreateNewUser;

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\UsersController;
use App\Http\Controllers\Api\PermissionController;

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

Route::post('forgot-password', [AuthController::class, 'forgotPassword']);

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    // Rutas protegidas por autenticación y verificación
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('user', [AuthController::class, 'user']);
    Route::get('/users', [AuthController::class, 'getUsers']);
    Route::post('update-password', [AuthController::class, 'updatePassword']);
    
    Route::post('reset-password', [AuthController::class, 'resetPassword']);
    Route::post('update-profile', [AuthController::class, 'updateProfile']);

    // Rutas relacionadas con roles
    Route::get('roles', [RoleController::class, 'index']); // Obtener una lista de roles
    Route::post('roles', [RoleController::class, 'store']); // Crear un nuevo rol
    Route::get('roles/{id}', [RoleController::class, 'show']); // Mostrar un rol específico
    Route::put('roles/{id}', [RoleController::class, 'update']); // Actualizar un rol existente
    Route::delete('roles/{id}', [RoleController::class, 'destroy']); // Eliminar un rol existente
    Route::get('roles-permissions', [RoleController::class, 'create']); // Mostrar listado de permisos
    Route::get('roles/{id}/edit', [RoleController::class, 'edit']); // Mostrar listado de roles y permisos del usuario a editar

    // Rutas relacionadas con usuarios
    Route::get('users-crud', [UsersController::class, 'index']); 
    Route::post('users-crud', [UsersController::class, 'store']); 
    Route::get('users-crud/{id}', [UsersController::class, 'show']); 
    Route::put('users-crud/{id}', [UsersController::class, 'update']); 
    Route::delete('users-crud/{id}', [UsersController::class, 'destroy']); 
    Route::get('users-create', [UsersController::class, 'create']); 
    Route::get('users-crud/{id}/edit', [UsersController::class, 'edit']); 

    // Rutas relacionadas con permisos
    Route::get('permissions', [PermissionController::class, 'index']);
    Route::post('permissions', [PermissionController::class, 'store']);
    Route::get('permissions/{id}', [PermissionController::class, 'show']);
    Route::put('permissions/{id}', [PermissionController::class, 'update']);
    Route::delete('permissions/{id}', [PermissionController::class, 'destroy']);
    Route::get('permissions/create', [PermissionController::class, 'create']);
    Route::get('permissions/{id}/edit', [PermissionController::class, 'edit']);
});


  
