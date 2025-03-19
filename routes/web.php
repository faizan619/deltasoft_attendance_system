<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
Route::redirect('/','/login');
// Route::get('/', function () {
//     return view('welcome');
// });

// Auth Routes
Route::get('/login',[UserController::class,'adminLogin'])->name('adminLogin');
Route::post('/login-check',[UserController::class,'LoginProceed'])->name('LoginProceed');
Route::post('/logout', [UserController::class, 'logout'])->name('logout');

// Dashboard Routes
Route::get('/dashboard',[UserController::class,'admin_dashboard'])->name("admin_dashboard");

Route::get('/user',[UserController::class,'user_dashboard'])->name('user_dashboard'); 