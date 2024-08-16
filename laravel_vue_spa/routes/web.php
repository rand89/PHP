<?php

use Illuminate\Support\Facades\Route;

Auth::routes();

Route::get('/{any}', function () {
    return view('welcome');
})->where('any', '.*');


Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
