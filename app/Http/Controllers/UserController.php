<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of users
     */
    public function index(Request $request)
    {
        // Simple auth check
        $user = $this->authenticateToken($request);
        if (!$user || $user->level !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Admin access required.'
            ], 403);
        }

        $query = User::query();

        // Search functionality
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('username', 'like', "%{$search}%")
                    ->orWhere('fullname', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filters
        if ($request->has('level') && $request->level != '') {
            $query->where('level', $request->level);
        }

        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        if ($request->has('type') && $request->type != '') {
            $query->where('type', $request->type);
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $users
        ]);
    }

    /**
     * Store a newly created user
     */
     public function store(Request $request)
    {
        // Auth check
        $user = $this->authenticateToken($request);
        if (!$user || $user->level !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Admin access required.'
            ], 403);
        }

        // Validation rules
        $rules = [
            'username' => 'required|string|max:50|unique:users',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|min:6',
            'level' => 'required|in:admin,user',
            'status' => 'required|in:aktif,tidak_aktif',
            'fullname' => 'nullable|string|max:100', // UBAH JADI NULLABLE
        ];

        // Add type validation ONLY for user level
        if ($request->level === 'user') {
            $rules['fullname'] = 'required|string|max:100'; // REQUIRED untuk user
            $rules['type'] = 'required|in:DS,SC,MF,FW';
        }

        // Validate request
        $validator = Validator::make($request->all(), $rules);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Prepare user data
        $userData = [
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'level' => $request->level,
            'status' => $request->status,
            'fullname' => $request->fullname, // SELALU SIMPAN FULLNAME
        ];

        // Add type ONLY for user level
        if ($request->level === 'user') {
            $userData['type'] = $request->type;
        } else {
            $userData['type'] = null; // NULL untuk admin
        }

        // Admin automatically verified
        if ($request->level === 'admin') {
            $userData['email_verified_at'] = now();
        }

        try {
            $newUser = User::create($userData);

            return response()->json([
                'success' => true,
                'message' => 'User berhasil dibuat',
                'data' => $newUser
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating user: ' . $e->getMessage()
            ], 500);
        }
    }


    /**
     * Display the specified user
     */
    public function show(User $user)
    {
        // Auth check
        $authUser = $this->authenticateToken(request());
        if (!$authUser || $authUser->level !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Admin access required.'
            ], 403);
        }

        return response()->json([
            'success' => true,
            'data' => $user
        ]);
    }

    /**
     * Update the specified user
     */
    public function update(Request $request, User $user)
    {
        // Auth check
        $authUser = $this->authenticateToken($request);
        if (!$authUser || $authUser->level !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Admin access required.'
            ], 403);
        }

        $rules = [
            'username' => ['required', 'string', 'max:50', Rule::unique('users')->ignore($user->id)],
            'email' => ['required', 'string', 'email', 'max:100', Rule::unique('users')->ignore($user->id)],
            'level' => 'required|in:admin,user',
            'status' => 'required|in:aktif,tidak_aktif',
            'fullname' => 'nullable|string|max:100', // UBAH JADI NULLABLE
        ];

        // Password optional saat update
        if ($request->filled('password')) {
            $rules['password'] = 'string|min:6';
        }

        // Validasi untuk user level
        if ($request->level === 'user') {
            $rules['fullname'] = 'required|string|max:100'; // REQUIRED untuk user
            $rules['type'] = 'required|in:DS,SC,MF,FW';
        }

        $validated = $request->validate($rules);

        $userData = [
            'username' => $validated['username'],
            'email' => $validated['email'],
            'level' => $validated['level'],
            'status' => $validated['status'],
            'fullname' => $validated['fullname'], // SELALU UPDATE FULLNAME
        ];

        // Update password jika diisi
        if ($request->filled('password')) {
            $userData['password'] = Hash::make($validated['password']);
        }

        // Update type untuk user, null untuk admin
        if ($validated['level'] === 'user') {
            $userData['type'] = $validated['type'];
        } else {
            $userData['type'] = null; // NULL untuk admin
        }

        $user->update($userData);

        return response()->json([
            'success' => true,
            'message' => 'User berhasil diupdate',
            'data' => $user->fresh()
        ]);
    }

    /**
     * Remove the specified user
     */
    public function destroy(User $user)
    {
        // Auth check
        $authUser = $this->authenticateToken(request());
        if (!$authUser || $authUser->level !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Admin access required.'
            ], 403);
        }

        // Prevent deleting current user
        if ($user->id === $authUser->id) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak dapat menghapus akun sendiri'
            ], 422);
        }

        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'User berhasil dihapus'
        ]);
    }

    /**
     * Toggle user status
     */
    public function toggleStatus(User $user)
    {
        // Auth check
        $authUser = $this->authenticateToken(request());
        if (!$authUser || $authUser->level !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Admin access required.'
            ], 403);
        }

        $newStatus = $user->status === 'aktif' ? 'tidak_aktif' : 'aktif';
        $user->update(['status' => $newStatus]);

        return response()->json([
            'success' => true,
            'message' => 'Status user berhasil diubah',
            'data' => $user->fresh()
        ]);
    }

    private function authenticateToken(Request $request)
    {
        $token = $request->bearerToken();

        if (!$token) {
            return null;
        }

        try {
            $decoded = base64_decode($token);
            $parts = explode('|', $decoded);

            if (count($parts) < 2) {
                return null;
            }

            $userId = $parts[0];
            $user = User::find($userId);

            return $user && $user->status === 'aktif' ? $user : null;
        } catch (\Exception $e) {
            return null;
        }
    }

    public function dashboard(Request $request)
    {
        // Auth check
        $authUser = $this->authenticateToken($request);
        if (!$authUser || $authUser->level !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Admin access required.'
            ], 403);
        }

        try {
            $stats = [
                'total' => User::count(),
                'DS' => User::where('type', 'DS')->count(), 
                'SC' => User::where('type', 'SC')->count(),
                'MF' => User::where('type', 'MF')->count(),
                'FW' => User::where('type', 'FW')->count(),
                'active' => User::where('status', 'aktif')->count(),
                'inactive' => User::where('status', 'tidak_aktif')->count(),
                'admin' => User::where('level', 'admin')->count(),
            ];

            return response()->json([
                'success' => true,
                'data' => $stats
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error loading dashboard stats: ' . $e->getMessage()
            ], 500);
        }
    }
}