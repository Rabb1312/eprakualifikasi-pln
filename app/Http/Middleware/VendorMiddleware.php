<?php
// app/Http/Middleware/VendorMiddleware.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class VendorMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $token = $request->bearerToken();
        
        if (!$token) {
            return response()->json([
                'success' => false,
                'message' => 'Token tidak ditemukan'
            ], 401);
        }

        $user = $this->authenticateToken($token);
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Token tidak valid'
            ], 401);
        }

        if ($user->level !== 'user') {
            return response()->json([
                'success' => false,
                'message' => 'Akses ditolak. Hanya vendor yang diizinkan.'
            ], 403);
        }

        // Attach user to request
        $request->merge(['authenticated_user' => $user]);
        
        return $next($request);
    }

    private function authenticateToken($token)
    {
        try {
            $decoded = base64_decode($token);
            $parts = explode('|', $decoded);
            
            if (count($parts) < 3) {
                return null;
            }

            $userId = $parts[0];
            $timestamp = $parts[2];

            // Check if token is not too old (24 hours)
            if (time() - $timestamp > 86400) {
                return null;
            }

            $user = \App\Models\User::find($userId);
            
            return $user && $user->status === 'aktif' ? $user : null;
        } catch (\Exception $e) {
            return null;
        }
    }
}