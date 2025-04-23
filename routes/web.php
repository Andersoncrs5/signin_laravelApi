<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\IsLogged;
use App\Http\Middleware\IsNotLogged;
use Illuminate\Support\Facades\Route;

Route::prefix('api/user')->controller(UserController::class)->group(function() { 
    Route::middleware(IsLogged::class)->group(function () {
        Route::get('/', "get")->name('user.get');
        Route::delete('/', "delete")->name('user.delete');
        Route::put('/', "update")->name('user.update');
        Route::get('/logout', "logout")->name('auth.logout');
    });
});

Route::prefix('api/auth')->controller(AuthController::class)->group(function() { 
    Route::post('/login', "login")->name('auth.login');
    Route::post('/register', "register")->name('auth.register'); 
});

Route::fallback(function(){
    return response()->json("This route not exists", 404);
});