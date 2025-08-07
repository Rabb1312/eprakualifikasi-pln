<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'companyName' => 'required|string|max:200',
            'companyType' => 'required|in:distributor,subcontractor,manufacturer,forwarder',
            'emailAddress' => 'required|email|unique:users,email',
            'username' => 'required|string|min:4|max:50|unique:users,username',
            'password' => 'required|string|min:6',
            'knownFrom' => 'required|in:website,instagram,facebook,other',
            'npwp' => 'nullable|string|max:25',
            'other' => 'required_if:knownFrom,other|nullable|string|max:200',
            'agreement' => 'required|accepted'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Mapping company type
            $typeMapping = [
                'distributor' => 'DS',
                'subcontractor' => 'SC',
                'manufacturer' => 'MF',
                'forwarder' => 'FW'
            ];

            $vendorType = $typeMapping[$request->companyType];

            // Create user account
            $user = User::create([
                'username' => $request->username,
                'email' => $request->emailAddress,
                'password' => Hash::make($request->password),
                'fullname' => $request->companyName,
                'level' => 'user',
                'type' => $vendorType,
                'status' => 'tidak_aktif', // Inactive until email verified
                'email_verified_at' => null
            ]);

            // Generate nomor vendor
            $nomorVendor = Vendor::generateNomorVendor($vendorType);

            // Create vendor profile dengan data dari registrasi
            $vendor = Vendor::create([
                'user_id' => $user->id,
                'nomor_vendor' => $nomorVendor,
                'tipe_perusahaan' => $vendorType,
                'nama_perusahaan' => $request->companyName,
                'npwp' => $request->npwp,
                'sumber_informasi' => $request->knownFrom === 'other' ? 'lainnya' : $request->knownFrom,
                'rekomendasi_dari' => $request->knownFrom === 'rekomendasi' ? $request->other : null,
                'lainnya' => $request->knownFrom === 'other' ? $request->other : null,
                'profile_completed' => false,
                'completion_percentage' => 0
            ]);

            // Calculate initial completion
            $vendor->calculateCompletion();

            // Send verification email
            $emailSent = $this->sendVerificationEmail($user);

            return response()->json([
                'success' => true,
                'message' => 'Registrasi berhasil! Silakan cek email untuk verifikasi.',
                'data' => [
                    'user_id' => $user->id,
                    'vendor_id' => $vendor->id,
                    'nomor_vendor' => $nomorVendor,
                    'email_sent' => $emailSent
                ]
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat registrasi: ' . $e->getMessage()
            ], 500);
        }
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string',
            'password' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Username dan password wajib diisi',
                'errors' => $validator->errors()
            ], 422);
        }

        // Find user by username or email
        $user = User::where('username', $request->username)
            ->orWhere('email', $request->username)
            ->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Username atau password salah'
            ], 401);
        }

        // Check if email is verified
        if (!$user->isVerified()) {
            return response()->json([
                'success' => false,
                'message' => 'Email belum diverifikasi. Silakan cek email Anda.',
                'requires_verification' => true
            ], 403);
        }

        // Check if user is active
        if (!$user->isActive()) {
            return response()->json([
                'success' => false,
                'message' => 'Akun Anda sedang tidak aktif. Hubungi administrator.'
            ], 403);
        }

        // Generate token (simplified - in production use Laravel Sanctum)
        $token = base64_encode($user->id . '|' . Str::random(40) . '|' . time());

        // Update last login
        $user->update(['last_login' => now()]);

        // Get vendor profile if user is vendor
        $vendorProfile = null;
        if ($user->level === 'user') {
            $vendorProfile = $user->vendor;
        }

        return response()->json([
            'success' => true,
            'message' => 'Login berhasil',
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'username' => $user->username,
                    'email' => $user->email,
                    'fullname' => $user->fullname,
                    'level' => $user->level,
                    'type' => $user->type,
                    'status' => $user->status
                ],
                'vendor' => $vendorProfile ? [
                    'id' => $vendorProfile->id,
                    'nomor_vendor' => $vendorProfile->nomor_vendor,
                    'tipe_perusahaan' => $vendorProfile->tipe_perusahaan,
                    'nama_perusahaan' => $vendorProfile->nama_perusahaan,
                    'profile_completed' => $vendorProfile->profile_completed,
                    'completion_percentage' => $vendorProfile->completion_percentage
                ] : null,
                'token' => $token
            ]
        ]);
    }

    public function verifyEmail(Request $request, $token)
    {
        try {
            // Find user by verification token
            $email = base64_decode($token);
            $user = User::where('email', $email)->first();

            if (!$user) {
                return view('email-verification', [
                    'type' => 'error',
                    'icon' => 'fa-times-circle',
                    'title' => 'Token Tidak Valid',
                    'message' => 'Link verifikasi tidak valid atau sudah kedaluwarsa.<br>Silakan lakukan registrasi ulang atau hubungi administrator.',
                    'details' => null,
                    'next_steps' => [
                        'Lakukan registrasi ulang dengan email yang benar',
                        'Pastikan link verifikasi tidak kedaluwarsa (24 jam)',
                        'Hubungi support jika masalah berlanjut'
                    ]
                ]);
            }

            if ($user->isVerified()) {
                // Get vendor info if exists
                $vendor = $user->vendor;

                return view('email-verification', [
                    'type' => 'info',
                    'icon' => 'fa-info-circle',
                    'title' => 'Email Sudah Terverifikasi',
                    'message' => 'Email Anda sudah terverifikasi sebelumnya.<br>Silakan login untuk melanjutkan.',
                    'details' => [
                        'email' => $user->email,
                        'username' => $user->username,
                        'nomor_vendor' => $vendor ? $vendor->nomor_vendor : 'Belum tersedia',
                        'status' => $user->status
                    ],
                    'next_steps' => [
                        'Login ke akun Anda',
                        'Lengkapi profil perusahaan',
                        'Upload dokumen yang diperlukan',
                        'Tunggu approval dari admin PLN'
                    ]
                ]);
            }

            // Verify email and activate user
            $user->update([
                'email_verified_at' => now(),
                'status' => 'aktif'
            ]);

            // Get vendor info
            $vendor = $user->vendor;

            return view('email-verification', [
                'type' => 'success',
                'icon' => 'fa-check-circle',
                'title' => 'Email Berhasil Diverifikasi!',
                'message' => 'Selamat! Email Anda telah berhasil diverifikasi.<br>Akun Anda sekarang aktif dan siap digunakan.',
                'details' => [
                    'email' => $user->email,
                    'username' => $user->username,
                    'perusahaan' => $user->fullname,
                    'nomor_vendor' => $vendor ? $vendor->nomor_vendor : 'Belum tersedia',
                    'tipe_vendor' => $vendor ? $vendor->tipe_perusahaan : 'Belum tersedia'
                ],
                'next_steps' => [
                    'Login dengan username dan password Anda',
                    'Lengkapi profil perusahaan di dashboard',
                    'Upload dokumen persyaratan',
                    'Tunggu verifikasi dari tim PLN',
                    'Mulai mengikuti tender setelah disetujui'
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Email verification error: ' . $e->getMessage());

            return view('email-verification', [
                'type' => 'error',
                'icon' => 'fa-exclamation-triangle',
                'title' => 'Terjadi Kesalahan',
                'message' => 'Maaf, terjadi kesalahan saat memverifikasi email Anda.<br>Silakan coba lagi atau hubungi administrator.',
                'details' => null,
                'next_steps' => [
                    'Coba klik link verifikasi sekali lagi',
                    'Pastikan koneksi internet stabil',
                    'Hubungi perencanaan@plnnpc.com jika masalah berlanjut'
                ]
            ]);
        }
    }

    public function resendVerification(Request $request)
{
    $validator = Validator::make($request->all(), [
        'email' => 'required|email|exists:users,email'
    ]);

    if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'message' => 'Email tidak valid atau tidak terdaftar',
            'errors' => $validator->errors()
        ], 422);
    }

    try {
        $user = User::where('email', $request->email)->first();

        if ($user->isVerified()) {
            return response()->json([
                'success' => false,
                'message' => 'Email sudah terverifikasi. Silakan login.'
            ], 400);
        }

        // Send verification email again
        $emailSent = $this->sendVerificationEmail($user);

        return response()->json([
            'success' => true,
            'message' => 'Email verifikasi berhasil dikirim ulang. Silakan cek kotak masuk Anda.',
            'data' => [
                'email' => $request->email,
                'email_sent' => $emailSent
            ]
        ]);

    } catch (\Exception $e) {
        Log::error('Resend verification error: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Terjadi kesalahan saat mengirim email verifikasi'
        ], 500);
    }
}

public function forgotPassword(Request $request)
{
    $validator = Validator::make($request->all(), [
        'email' => 'required|email|exists:users,email'
    ]);

    if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'message' => 'Email tidak valid atau tidak terdaftar',
            'errors' => $validator->errors()
        ], 422);
    }

    try {
        $user = User::where('email', $request->email)->first();

        if (!$user->isVerified()) {
            return response()->json([
                'success' => false,
                'message' => 'Email belum diverifikasi. Silakan verifikasi email terlebih dahulu.',
                'requires_verification' => true
            ], 400);
        }

        // Generate reset token
        $resetToken = base64_encode($user->email . '|' . Str::random(40) . '|' . time());
        
        // Store reset token (in production, store in database)
        // For now, we'll use the token directly in email
        
        $emailSent = $this->sendPasswordResetEmail($user, $resetToken);

        return response()->json([
            'success' => true,
            'message' => 'Link reset password berhasil dikirim ke email Anda.',
            'data' => [
                'email' => $request->email,
                'email_sent' => $emailSent
            ]
        ]);

    } catch (\Exception $e) {
        Log::error('Forgot password error: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Terjadi kesalahan saat mengirim email reset password'
        ], 500);
    }
}

public function resetPassword(Request $request, $token)
{
    $validator = Validator::make($request->all(), [
        'password' => 'required|string|min:6|confirmed'
    ]);

    if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'message' => 'Validation failed',
            'errors' => $validator->errors()
        ], 422);
    }

    try {
        // Decode token
        $decoded = base64_decode($token);
        $parts = explode('|', $decoded);

        if (count($parts) < 3) {
            return view('password-reset-result', [
                'type' => 'error',
                'title' => 'Token Tidak Valid',
                'message' => 'Link reset password tidak valid atau sudah kedaluwarsa.'
            ]);
        }

        $email = $parts[0];
        $timestamp = $parts[2];

        // Check if token is not too old (1 hour)
        if (time() - $timestamp > 3600) {
            return view('password-reset-result', [
                'type' => 'error',
                'title' => 'Token Kedaluwarsa',
                'message' => 'Link reset password sudah kedaluwarsa. Silakan minta link baru.'
            ]);
        }

        $user = User::where('email', $email)->first();

        if (!$user) {
            return view('password-reset-result', [
                'type' => 'error',
                'title' => 'User Tidak Ditemukan',
                'message' => 'User dengan email tersebut tidak ditemukan.'
            ]);
        }

        // Update password
        $user->update([
            'password' => Hash::make($request->password)
        ]);

        return view('password-reset-result', [
            'type' => 'success',
            'title' => 'Password Berhasil Diubah!',
            'message' => 'Password Anda berhasil diubah. Silakan login dengan password baru.',
            'user' => $user
        ]);

    } catch (\Exception $e) {
        Log::error('Reset password error: ' . $e->getMessage());
        
        return view('password-reset-result', [
            'type' => 'error',
            'title' => 'Terjadi Kesalahan',
            'message' => 'Terjadi kesalahan saat mengubah password. Silakan coba lagi.'
        ]);
    }
}

public function showResetForm($token)
{
    try {
        // Validate token first
        $decoded = base64_decode($token);
        $parts = explode('|', $decoded);

        if (count($parts) < 3) {
            return view('password-reset-result', [
                'type' => 'error',
                'title' => 'Token Tidak Valid',
                'message' => 'Link reset password tidak valid.'
            ]);
        }

        $timestamp = $parts[2];

        // Check if token is not too old (1 hour)
        if (time() - $timestamp > 3600) {
            return view('password-reset-result', [
                'type' => 'error',
                'title' => 'Token Kedaluwarsa',
                'message' => 'Link reset password sudah kedaluwarsa. Silakan minta link baru.'
            ]);
        }

        return view('password-reset-form', [
            'token' => $token
        ]);

    } catch (\Exception $e) {
        return view('password-reset-result', [
            'type' => 'error',
            'title' => 'Terjadi Kesalahan',
            'message' => 'Link reset password tidak valid.'
        ]);
    }
}

private function sendPasswordResetEmail(User $user, $token)
{
    try {
        $resetUrl = url('/reset-password/' . $token);

        $emailData = [
            'user' => $user,
            'url' => $resetUrl,
            'expires_at' => now()->addHour()->format('Y-m-d H:i:s')
        ];

        Mail::send('emails.password-reset', $emailData, function ($message) use ($user) {
            $message->to($user->email, $user->fullname)
                    ->subject('🔑 Reset Password PLN E-Prakualifikasi')
                    ->from(config('mail.from.address'), config('mail.from.name'));
        });

        Log::info('Password reset email sent successfully to: ' . $user->email);
        return true;

    } catch (\Exception $e) {
        Log::error('Failed to send password reset email: ' . $e->getMessage());
        return false;
    }
}

    public function logout(Request $request)
    {
        // In production, invalidate the token in database/cache
        return response()->json([
            'success' => true,
            'message' => 'Logout berhasil'
        ]);
    }

    // FIXED METHOD ME
    public function me(Request $request)
    {
        $user = $this->authenticateToken($request);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 401);
        }

        $vendorProfile = null;
        if ($user->level === 'user') {
            $vendorProfile = $user->vendor;
        }

        return response()->json([
            'success' => true,
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'username' => $user->username,
                    'email' => $user->email,
                    'fullname' => $user->fullname,
                    'level' => $user->level,
                    'type' => $user->type,
                    'status' => $user->status,
                    'email_verified_at' => $user->email_verified_at,
                    'last_login' => $user->last_login
                ],
                'vendor' => $vendorProfile ? [
                    'id' => $vendorProfile->id,
                    'nomor_vendor' => $vendorProfile->nomor_vendor,
                    'tipe_perusahaan' => $vendorProfile->tipe_perusahaan,
                    'nama_perusahaan' => $vendorProfile->nama_perusahaan,
                    'profile_completed' => $vendorProfile->profile_completed,
                    'completion_percentage' => $vendorProfile->completion_percentage,
                    'verified_at' => $vendorProfile->verified_at
                ] : null
            ]
        ]);
    }

    // FIXED EMAIL SENDING
    private function sendVerificationEmail(User $user)
{
    try {
        $token = base64_encode($user->email);
        // Use web route instead of API route for better UX
        $verificationUrl = url('/verify-email/' . $token);

        // Data untuk email template
        $emailData = [
            'user' => $user,
            'url' => $verificationUrl,
            'company_name' => $user->fullname,
            'expires_at' => now()->addHours(24)->format('Y-m-d H:i:s')
        ];

        // Send email using Mail facade
        Mail::send('emails.verify', $emailData, function ($message) use ($user) {
            $message->to($user->email, $user->fullname)
                    ->subject('🔐 Verifikasi Email PLN E-Prakualifikasi')
                    ->from(config('mail.from.address'), config('mail.from.name'));
        });

        Log::info('Verification email sent successfully to: ' . $user->email);
        return true;

    } catch (\Exception $e) {
        Log::error('Failed to send verification email: ' . $e->getMessage());
        return false;
    }
}

    // FIXED TOKEN AUTHENTICATION
    private function authenticateToken(Request $request)
    {
        $token = $request->bearerToken();

        if (!$token) {
            return null;
        }

        try {
            $decoded = base64_decode($token);
            $parts = explode('|', $decoded);

            if (count($parts) < 3) { // FIXED: should be 3 parts (id|random|timestamp)
                return null;
            }

            $userId = $parts[0];
            $timestamp = $parts[2];

            // Check if token is not too old (24 hours)
            if (time() - $timestamp > 86400) {
                return null;
            }

            $user = User::find($userId);

            return $user && $user->isActive() ? $user : null;
        } catch (\Exception $e) {
            return null;
        }
    }
}