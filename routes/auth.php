<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\UserController;
use App\Http\Controllers\Auth\AdminController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;


//users
Route::get('/register', [UserController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [UserController::class, 'store'])->name('user.register');
Route::get('/login', [UserController::class, 'showLoginForm'])->name('login');
Route::post('/login', [UserController::class, 'login'])->name('user.login');
Route::get('logout', [UserController::class, 'logout'])->name('user.logout');

// forget-reset
Route::get('/forgot-password', [ForgotPasswordController::class, 'showForgotPasswordForm'])->name('user.password.request');
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLink'])->name('user.password.email');
Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetPasswordForm'])->name('password.reset');
Route::post('/reset-Password', [ResetPasswordController::class, 'resetPassword'])->name('user.password.update');



// --------------------------
//admin 
Route::group(['prefix' => 'admin'], function () {
    Route::get('/login', [AdminController::class, 'showLoginForm'])->name('admin.login.form');
    Route::post('/login', [AdminController::class, 'login'])->name('admin.login');
    Route::get('logout', [AdminController::class, 'logout'])->name('admin.logout');
    

    // forget-reset
    Route::get('/forgot-password', [ForgotPasswordController::class, 'adminShowForgotPasswordForm'])->name('admin.password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'adminSendResetLink'])->name('admin.password.email');
    Route::get('/reset-password/{token}', [ResetPasswordController::class, 'adminShowResetPasswordForm'])->name('password.reset');
    Route::post('/reset-Password', [ResetPasswordController::class, 'adminResetPassword'])->name('admin.password.update');

});





















?>