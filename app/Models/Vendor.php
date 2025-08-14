<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    protected $fillable = [
        'user_id', 'nomor_vendor', 'tipe_perusahaan', 'nama_perusahaan', 'npwp',
        'sumber_informasi', 'rekomendasi_dari', 'lainnya',
        'nama_perusahaan_induk', 'alamat_perusahaan_induk', 'alamat',
        'alamat_kantor_pusat', 'alamat_kantor_operasional', 'kode_pos',
        'phone', 'phone_pusat', 'website', 'contact_person', 'top_level',
        'mid_level', 'sales_marketing', 'tanggal_berdiri', 'bagian_grup',
        'tanggal_beroperasi', 'modal_dasar', 'modal_dikeluarkan',
        'pemegang_saham', 'nama_direktur', 'jumlah_karyawan',
        'kepemilikan', 'kepemilikan_armada', 'profile_completed',
        'completion_percentage', 'verified_at', 'verified_by'
    ];

    protected $casts = [
        'modal_dasar' => 'decimal:2',
        'modal_dikeluarkan' => 'decimal:2',
        'profile_completed' => 'boolean',
        'completion_percentage' => 'decimal:2',
        'verified_at' => 'datetime',
        'contact_person' => 'array',
        'top_level' => 'array',
        'mid_level' => 'array',
        'sales_marketing' => 'array'
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    // Generate nomor vendor otomatis
    public static function generateNomorVendor($tipe)
    {
        $prefix = $tipe . '-';
        $lastNumber = static::where('tipe_perusahaan', $tipe)
            ->where('nomor_vendor', 'LIKE', $prefix . '%')
            ->orderByRaw('CAST(SUBSTRING(nomor_vendor, 4) AS UNSIGNED) DESC')
            ->first();

        if ($lastNumber) {
            $lastNum = (int) substr($lastNumber->nomor_vendor, 3);
            $newNum = $lastNum + 1;
        } else {
            $newNum = 1;
        }

        return $prefix . str_pad($newNum, 4, '0', STR_PAD_LEFT);
    }

    // Calculate completion percentage
    public function calculateCompletion()
{
    // Get required fields for this vendor type from database
    $fieldMappings = \App\Models\VendorFieldMapping::where('tipe_perusahaan', $this->tipe_perusahaan)
        ->where('is_required', true)
        ->get();

    $totalFields = $fieldMappings->count();
    $filledFields = 0;

    foreach ($fieldMappings as $field) {
        $value = $this->{$field->field_name};
        
        // Check different field types
        if ($field->field_type === 'json') {
            // For JSON fields (contact_person, etc)
            if (is_array($value) && !empty($value)) {
                $filledFields++;
            }
        } elseif ($field->field_type === 'date') {
            // For date fields
            if (!empty($value)) {
                $filledFields++;
            }
        } else {
            // For text, textarea, number, etc
            if (!empty($value) && $value !== null) {
                $filledFields++;
            }
        }
    }

    $percentage = $totalFields > 0 ? round(($filledFields / $totalFields) * 100, 2) : 0;
    
    $this->update([
        'completion_percentage' => $percentage,
        'profile_completed' => $percentage >= 100
    ]);

    return $percentage;
}

    // Format contact untuk display
    public function formatContact($contactType)
    {
        $contact = $this->$contactType;
        if (is_array($contact) && !empty($contact['nama'])) {
            return $contact['nama'] . (!empty($contact['jabatan']) ? ' (' . $contact['jabatan'] . ')' : '');
        }
        return '-';
    }

    // Scope queries
    public function scopeByType($query, $type)
    {
        return $query->where('tipe_perusahaan', $type);
    }

    public function scopeCompleted($query)
    {
        return $query->where('profile_completed', true);
    }

    public function scopeVerified($query)
    {
        return $query->whereNotNull('verified_at');
    }

    public function getTanggalBerdiriAttribute($value)
    {
        return $value; // Return as is (Y-m-d format for input date)
    }

    public function getTanggalBeroperasiAttribute($value)
    {
        return $value; // Return as is (Y-m-d format for input date)
    }

    public function setTanggalBerdiriAttribute($value)
    {
        $this->attributes['tanggal_berdiri'] = $value;
    }

    public function setTanggalBeroperasiAttribute($value)
    {
        $this->attributes['tanggal_beroperasi'] = $value;
    }

     public function subcontractor()
    {
        return $this->hasOne(Subcontractor::class);
    }

    public function distributor()
    {
        // return $this->hasOne(Distributor::class); // untuk future implementation
    }

    public function manufacturer()
    {
        // return $this->hasOne(Manufacturer::class); // untuk future implementation
    }

    public function forwarder()
    {
        // return $this->hasOne(Forwarder::class); // untuk future implementation
    }

    public function getTypeSpecificData()
    {
        switch ($this->tipe_perusahaan) {
            case 'SC':
                return $this->subcontractor;
            case 'DS':
                return $this->distributor;
            case 'MF':
                return $this->manufacturer;
            case 'FW':
                return $this->forwarder;
            default:
                return null;
        }
    }

     public function getSubcontractorData()
    {
        if ($this->tipe_perusahaan === 'SC') {
            return $this->subcontractor;
        }
        return null;
    }

    public function createSubcontractorProfile()
    {
        if ($this->tipe_perusahaan === 'SC' && !$this->subcontractor) {
            $subcontractor = Subcontractor::create(['vendor_id' => $this->id]);
            $subcontractor->initializeDefaults();
            return $subcontractor;
        }
        return $this->subcontractor;
    }
}