<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentController;

Route::get('/', function () {
    return redirect('/login');
});

Route::get('/register', [StudentController::class, 'showRegister']);
Route::post('/register', [StudentController::class, 'register']);

Route::get('/login', [StudentController::class, 'showLogin'])->name('login');
Route::post('/login', [StudentController::class, 'login']);

Route::get('/dashboard', [StudentController::class, 'dashboard']);
Route::get('/edit-profile', [StudentController::class, 'showEditProfile']);
Route::post('/update-profile', [StudentController::class, 'updateProfile']);

Route::get('/logout', [StudentController::class, 'logout']);
Route::get('/logs', [StudentController::class, 'logs']);