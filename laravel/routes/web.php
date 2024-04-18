<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;

Route::get('/', [AuthController::class, 'index'])->name('pageRegister');
Route::post('/', [AuthController::class, 'register'])->name('register');
Route::get('/login', [AuthController::class, 'pageLogin'])->name('pageLogin');
Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::middleware('auth')->group(function() {
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/users', [UserController::class, 'index'])->name('index');
    Route::get('/create', [UserController::class, 'create'])->name('create');
    Route::post('/users', [UserController::class, 'store'])->name('store');
    Route::get('/users/{user}', [UserController::class, 'show'])->name('show');

    Route::middleware('profile')->group(function() {
        Route::get('/edit/{id}', [UserController::class, 'editInfo'])->name('editInfo');
        Route::patch('/edit/{id}', [UserController::class, 'updateInfo'])->name('updateInfo');
        Route::get('/status/{id}', [UserController::class, 'editStatus'])->name('editStatus');
        Route::patch('/status/{id}', [UserController::class, 'updateStatus'])->name('updateStatus');
        Route::get('/photo/{id}', [UserController::class, 'editPhoto'])->name('editPhoto');
        Route::patch('/photo/{id}', [UserController::class, 'updatePhoto'] )->name('updatePhoto');
        Route::get('/security/{id}', [UserController::class, 'editSecurity'])->name('editSecurity');
        Route::patch('/security/{id}', [UserController::class, 'updateSecurity'])->name('updateSecurity');
        Route::get('/delete/{id}', [UserController::class, 'destroy'])->name('destroy');
    });   
});

Route::fallback(function () {
    return redirect(route('index'));
});