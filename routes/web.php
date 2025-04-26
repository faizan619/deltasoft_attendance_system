<?php

use App\Http\Controllers\AttendanceController;
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

// Admin Part
Route::get('/add-employee',[UserController::class,'add_employee'])->name("add_employee");
Route::post('/save-employee',[UserController::class,'save_employee'])->name("save_employee");
Route::get('/emp-list',[UserController::class,'ViewEmpList'])->name('emp_list');

Route::get('/user',[AttendanceController::class,'user_dashboard'])->name('user_dashboard'); 
Route::post('/user-attendance',[AttendanceController::class,'store'])->name('attendance.store');
Route::get('/user-reason',[AttendanceController::class,'attendanceReason'])->name('attendanceReason');
Route::get('/user-reached',[AttendanceController::class,'reached'])->name('reached');