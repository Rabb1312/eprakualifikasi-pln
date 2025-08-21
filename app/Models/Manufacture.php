<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Manufacture extends Model
{
    protected $fillable = [
        'vendor_id',
        'product_types',
        'personnel',
        'plants',
        'after_sales',
        'engineering_design',
        'inventory',
        'subcontracting',
        'code_standard',
    ];

    protected $casts = [
        'product_types' => 'array',
        'personnel' => 'array',
        'plants' => 'array',
        'after_sales' => 'array',
        'engineering_design' => 'array',
        'inventory' => 'array',
        'code_standard' => 'array',
    ];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }
}