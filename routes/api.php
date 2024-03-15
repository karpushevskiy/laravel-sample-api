<?php
/*
 * GorKa Team
 * Copyright (c) 2024  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

use App\Http\Controllers\Api\Admin\UsersController;
use App\Http\Controllers\Api\Common\AuthenticationController;
use App\Http\Controllers\Api\Helpers\HelperListsController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Authentication & Passwords API Routes
|--------------------------------------------------------------------------
*/
Route::group(['middleware' => ['api']], function () {
    // TODO: Check, if we need route names
    // Authentication routes
    Route::group(['prefix' => 'auth', 'as' => 'auth.', 'middleware' => []], function () {
        Route::post('/login', [AuthenticationController::class, 'login']);
        Route::get('/user', [AuthenticationController::class, 'user'])->middleware(['api_auth']);
        Route::get('/logout', [AuthenticationController::class, 'logout']);
        Route::post('/register', [AuthenticationController::class, 'register']);
    });

    // Password routes
    Route::group(['prefix' => 'password', 'as' => 'password.', 'middleware' => []], function () {
        Route::post('/forgot', [AuthenticationController::class, 'forgotPassword']);
        Route::post('/reset', [AuthenticationController::class, 'resetPassword']);
        Route::post('/reset/validate', [AuthenticationController::class, 'validateResetPasswordToken']);
    });
});

/*
|--------------------------------------------------------------------------
| Protected API Routes
|--------------------------------------------------------------------------
*/
Route::group(['middleware' => ['api_auth']], function () {
    //======================================================================
    // Common routes
    //======================================================================

    // Helpers routes
    Route::group(['prefix' => 'helpers', 'as' => 'helpers.', 'middleware' => []], function () {
        Route::group(['prefix' => 'methods', 'as' => 'methods.', 'middleware' => []], function () {
//            Route::get('/some-method', [HelperMethodsController::class, 'someMethod'])->name('some');
        });

        Route::group(['prefix' => 'lists', 'as' => 'lists.', 'middleware' => []], function () {
            Route::get('/roles', [HelperListsController::class, 'showRolesList'])->name('roles');
            Route::get('/users', [HelperListsController::class, 'showUsersList'])->name('users');
        });
    });


    //======================================================================
    // Admin routes
    //======================================================================

    // Users routes
    Route::group(['prefix' => 'users', 'as' => 'users.', 'middleware' => []], function () {
        Route::get('/', [UsersController::class, 'index'])->name('index');
        Route::get('/{user}', [UsersController::class, 'show'])->name('show');
        Route::get('/{user}/edit', [UsersController::class, 'edit'])->name('edit');
        Route::post('/', [UsersController::class, 'store'])->name('store');
        Route::patch('/{user}', [UsersController::class, 'update'])->name('update');
        Route::delete('/{user}', [UsersController::class, 'destroy'])->name('destroy');
        Route::post('/{user}/restore', [UsersController::class, 'restore'])->name('restore');
    });
});
