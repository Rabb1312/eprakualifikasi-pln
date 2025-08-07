<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'username',
        'fullname', 
        'email',
        'password',
        'level',
        'type',
        'status',
        'last_login',
        'email_verified_at'
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login' => 'datetime',
        'password' => 'hashed',
    ];

    // Relationships
    public function vendor()
    {
        return $this->hasOne(Vendor::class);
    }

    public function emailVerificationTokens()
    {
        return $this->hasMany(EmailVerificationToken::class);
    }

    public function loginHistory()
    {
        return $this->hasMany(UserLoginHistory::class);
    }

    // Scopes
    public function scopeAdmins($query)
    {
        return $query->where('level', 'admin');
    }

    public function scopeUsers($query)
    {
        return $query->where('level', 'user');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'aktif');
    }

    // Methods untuk check status - TAMBAHAN INI YANG HILANG
    public function isVerified()
    {
        return !is_null($this->email_verified_at);
    }

    public function isActive()  // METHOD INI HILANG - PERLU DITAMBAH
    {
        return $this->status === 'aktif';
    }

    // Accessors
    public function getIsAdminAttribute()
    {
        return $this->level === 'admin';
    }

    public function getIsActiveAttribute()
    {
        return $this->status === 'aktif';
    }

    public function getIsVerifiedAttribute()
    {
        return $this->isVerified();
    }
}