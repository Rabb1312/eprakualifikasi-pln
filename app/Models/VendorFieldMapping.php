<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorFieldMapping extends Model
{
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

    // Get required fields by vendor type
    public static function getRequiredFieldsByType($tipe)
    {
        return static::where('tipe_perusahaan', $tipe)
            ->where('is_required', true)
            ->orderBy('field_order')
            ->get();
    }

    // Get all fields by vendor type
    public static function getFieldsByType($tipe)
    {
        return static::where('tipe_perusahaan', $tipe)
            ->orderBy('field_order')
            ->get();
    }
}