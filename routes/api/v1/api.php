<?php

use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\api\v1\LoginController;
use \App\Http\Controllers\api\v1\RoleController;
use \App\Http\Controllers\api\v1\PermissionController;
use \App\Http\Controllers\api\v1\TaskController;
use \App\Http\Controllers\api\v1\FileSystem;
Route::post('/auth/login', [LoginController::class, 'login']);
Route::post('/auth/register', [LoginController::class, 'register']);
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/current-user', [LoginController::class, 'current_user']);
    Route::prefix('/role')->group(function (){
        Route::put('/',[RoleController::class, 'add']);
        Route::get('/',[RoleController::class, 'index']);
        Route::patch('/', [RoleController::class, 'update_role']);
        Route::delete('/', [RoleController::class, 'delete']);
        Route::patch('/permission', [RoleController::class, 'change_permission']);
        Route::get('/permission', [RoleController::class, 'get_permission_by_role']);

    });
    Route::prefix('/permission')->group(function(){
        Route::get('/', [PermissionController::class, 'index']);
    });
    Route::prefix('/task')->group(function(){
        Route::get('/from-me', [TaskController::class, 'from_me']);
        Route::get('/to-me', [TaskController::class, 'to_me']);
        Route::put('/',[TaskController::class, 'add_task']);
    });
    Route::prefix('/folders')->group(function(){
        Route::get('/',[FileSystem::class, 'get_folders']);
        Route::put('/',[FileSystem::class, 'create_folder']);
        Route::patch('/',[FileSystem::class, 'update_folder']);
        Route::delete('/', [FileSystem::class, 'delete_folder']);
        Route::prefix('/file')->group(function (){
            Route::put('/', [FileSystem::class, 'add_file']);
            Route::delete('/', [FileSystem::class, 'remove_file']);
            Route::get('/', [FileSystem::class, 'get_files']);
        });
    });
});

Route::post('/', [FileSystem::class, 'add_file']);
