<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;


Route::prefix('api/user')->controller(UserController::class)->group(function() { 
    Route::get('/', "get")->name('user.get');
    Route::delete('/', "delete")->name('user.delete');
    Route::put('/', "update")->name('user.update');
});

Route::prefix('api/auth')->controller(AuthController::class)->group(function() { 
    Route::post('/login', "login")->name('auth.login');
    Route::post('/register', "register")->name('auth.register');
    Route::get('/logout', "logout")->name('auth.logout');
});

Route::fallback(function(){
    return response()->json("This route not exists", 404);
});