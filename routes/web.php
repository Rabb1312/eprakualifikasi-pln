<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Email verification route (web-based for better UX)
Route::get('/verify-email/{token}', [AuthController::class, 'verifyEmail'])->name('verify.email');

// Password reset routes
Route::get('/reset-password/{token}', [AuthController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password/{token}', [AuthController::class, 'resetPassword'])->name('password.update');

// Redirect old API verification to web version  
Route::get('/api/auth/verify-email/{token}', function($token) {
    return redirect('/verify-email/' . $token);
});

// SPA route (catch-all for Vue.js frontend)
Route::get('/{any}', function () {
    return view('app');
})->where('any', '.*');