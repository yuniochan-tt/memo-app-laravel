<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\MemoController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Authentication and registration views
Route::get('/', [LoginController::class, 'showLoginForm'])->name('login.index');
Route::get('/user', [RegisterController::class, 'showRegistrationForm'])->name('user.register');
Route::post('/user/register', [RegisterController::class, 'register'])->name('register');

// Authenticated routes group
Route::group(['middleware' => ['auth']], function() {
    Route::get('/memo', [MemoController::class, 'index'])->name('memo.index');
    Route::get('/memo/add', [MemoController::class, 'add'])->name('memo.add');
    Route::post('/memo/update', [MemoController::class, 'update'])->name('memo.update');
    
    // Route for deleting a memo
    Route::post('/memo/delete', [MemoController::class, 'delete'])->name('memo.delete');
});

// Register Laravel authentication routes except standard register (prevents route conflicts)
Auth::routes(['register' => false]);