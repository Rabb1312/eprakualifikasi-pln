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
        'tanggal_berdiri' => 'date',
        'tanggal_beroperasi' => 'date',
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
        $fieldMapping = VendorFieldMapping::where('tipe_perusahaan', $this->tipe_perusahaan)
            ->where('is_required', true)
            ->get();

        $totalFields = $fieldMapping->count();
        $filledFields = 0;

        foreach ($fieldMapping as $field) {
            $value = $this->{$field->field_name};
            
            // Check JSON fields
            if ($field->field_type === 'json') {
                if (is_array($value) && !empty($value['nama']) && !empty($value['jabatan'])) {
                    $filledFields++;
                }
            } else {
                if (!empty($value)) {
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
}