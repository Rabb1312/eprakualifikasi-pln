<?php
// File: app/Models/VendorFieldMapping.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorFieldMapping extends Model
{
    use HasFactory;

    protected $fillable = [
        'tipe_perusahaan',
        'field_name',
        'field_label',
        'is_required',
        'field_order',
        'field_type',
        'field_options'
    ];

    protected $casts = [
        'is_required' => 'boolean',
        'field_options' => 'array'
    ];

    // Scope untuk mendapatkan field berdasarkan tipe perusahaan
    public function scopeForVendorType($query, $vendorType)
    {
        return $query->where('tipe_perusahaan', $vendorType);
    }

    // Scope untuk field yang required
    public function scopeRequired($query)
    {
        return $query->where('is_required', true);
    }
}