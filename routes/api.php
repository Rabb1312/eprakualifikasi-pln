<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VendorController; 

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
| Here is where you can register API routes for your application.
*/

// Public routes (no authentication required)
Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/verify-email/{token}', [AuthController::class, 'verifyEmail']);
    Route::post('/resend-verification', [AuthController::class, 'resendVerification']);
    Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
});

// Protected routes (require authentication)
Route::middleware(['api', 'cors'])->group(function () {
    
    // Auth routes
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']); // Get current user info
    
    // Dashboard routes
    Route::get('/dashboard/stats', [UserController::class, 'dashboard']);
    
    // Vendor routes (untuk level user)
    Route::prefix('vendor')->group(function () {
        Route::get('/profile', [VendorController::class, 'show']);
        Route::put('/profile', [VendorController::class, 'update']);
        Route::get('/field-mappings', [VendorController::class, 'getFieldMappings']);
    });
    
    // Admin only routes
    Route::middleware('admin')->group(function () {
        // User management
        Route::apiResource('users', UserController::class);
        Route::patch('users/{user}/toggle-status', [UserController::class, 'toggleStatus']);
        
        // Vendor management
        Route::prefix('admin/vendors')->group(function () {
            Route::get('/', [VendorController::class, 'index']);
            Route::get('/{id}', [VendorController::class, 'showAdmin']);
            Route::patch('/{id}/verify', [VendorController::class, 'verify']);
            Route::patch('/{id}/reject', [VendorController::class, 'reject']);
        });
        
        // Admin dashboard
        Route::get('/admin/dashboard/stats', [UserController::class, 'adminDashboard']);
    });
});

// Fallback route untuk API
Route::fallback(function () {
    return response()->json([
        'success' => false,
        'message' => 'API endpoint not found'
    ], 404);
});