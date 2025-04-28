<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\checkAdmin;
use App\Http\Middleware\checkUser;
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
Route::get('/add-employee',[UserController::class,'add_employee'])->name("add_employee")->middleware([checkAdmin::class]);
Route::post('/save-employee',[UserController::class,'save_employee'])->name("save_employee")->middleware([checkAdmin::class]);
Route::get('/emp-list',[UserController::class,'ViewEmpList'])->name('emp_list')->middleware([checkAdmin::class]);
Route::post('/update-employee',[UserController::class,'update_employee'])->name('update_employee')->middleware([checkAdmin::class]);

Route::get('/user',[AttendanceController::class,'user_dashboard'])->name('user_dashboard');     // Role is Already Checking in Controller so no need of middleware here
Route::post('/user-attendance',[AttendanceController::class,'store'])->name('attendance.store')->middleware([checkUser::class]);
Route::get('/user-reason',[AttendanceController::class,'attendanceReason'])->name('attendanceReason')->middleware([checkUser::class]);
Route::get('/user-reached',[AttendanceController::class,'reached'])->name('reached')->middleware([checkUser::class]);