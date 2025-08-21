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
Route::middleware(['api'])->group(function () {

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
        // Subcontractor specific routes
        Route::get('/subcontractor/tabs', [VendorController::class, 'getSubcontractorTabs']);
        Route::put('/subcontractor', [VendorController::class, 'updateSubcontractor']);
        //Distributor specific routes
        Route::get('/distributor/tabs', [VendorController::class, 'getDistributorTabs']);
        Route::put('/distributor', [VendorController::class, 'updateDistributor']);
        // Forwarder specific routes
        Route::get('/forwarder/tabs', [VendorController::class, 'getForwarderTabs']);
        Route::put('/forwarder', [VendorController::class, 'updateForwarder']);
        // Manufacture specific routes
        Route::get('/manufacture/tabs', [VendorController::class, 'getManufactureTabs']);
        Route::put('/manufacture', [VendorController::class, 'updateManufacture']);
    });

    // ✅ FIXED: Document routes with proper structure
    Route::prefix('vendor/documents')->group(function () {
        Route::get('/', [VendorController::class, 'getVendorDocuments'])->name('vendor.documents.index');
        Route::post('/upload', [VendorController::class, 'uploadDocument'])->name('vendor.documents.upload');

        // ✅ Parameterized routes AFTER specific routes
        Route::get('/{documentId}/download', [VendorController::class, 'downloadDocument'])->name('vendor.documents.download');
        Route::delete('/{documentId}', [VendorController::class, 'deleteDocument'])->name('vendor.documents.delete');
        Route::get('/documents{id}/deleteable-count', [VendorController::class, 'getDeleteableFilesCount'])->middleware('api');
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