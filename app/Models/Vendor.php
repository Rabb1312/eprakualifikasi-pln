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
    $totalFields = 0;
    $filledFields = 0;

    // 1. TAB GENERAL INFORMATION - Dari tabel vendors (KECUALI holding fields)
    $excludedHoldingFields = [
        'holding_nama_perusahaan', 'holding_tanggal_berdiri', 'holding_alamat',
        'holding_phone', 'holding_modal_dasar', 'holding_modal_dikeluarkan',
        'holding_pemegang_saham', 'holding_contact_person', 'holding_nama_direktur'
    ];

    $fieldMappings = \App\Models\VendorFieldMapping::where('tipe_perusahaan', $this->tipe_perusahaan)
        ->where('is_required', true)
        ->whereNotIn('field_name', $excludedHoldingFields) // Exclude holding fields
        ->get();

    foreach ($fieldMappings as $field) {
        $value = $this->{$field->field_name};
        $totalFields++;
        
        if ($field->field_type === 'json') {
            if (is_array($value) && !empty($value)) {
                $filledFields++;
            }
        } elseif ($field->field_type === 'date') {
            if (!empty($value)) {
                $filledFields++;
            }
        } else {
            if (!empty($value) && $value !== null) {
                $filledFields++;
            }
        }
    }

    // 2. TAB LAINNYA - Dari tabel subcontractors (KECUALI holding company)
    if ($this->tipe_perusahaan === 'SC') {
        $subcontractor = $this->subcontractor;
        
        if ($subcontractor) {
            // Define fields yang perlu dihitung dari subcontractors table
            // TIDAK TERMASUK holding company fields
            $subcontractorFields = [
                // Tab 3: Facilities Company
                'facilities' => 'json',
                'other_services' => 'text',
                
                // Tab 4: Total Permanent Construction Employees
                'employees' => 'json',
                
                // Tab 5: Scope of Work
                'scope_of_work' => 'text',
                
                // Tab 6: Major Projects 3-5
                'major_projects' => 'json',
                
                // Tab 7: Knowledgeable
                'local_regulation_knowledge' => 'enum',
                'regulation_knowledge_details' => 'text'
            ];

            foreach ($subcontractorFields as $fieldName => $fieldType) {
                $value = $subcontractor->{$fieldName};
                $totalFields++;
                
                if ($fieldType === 'json') {
                    if (is_array($value) && !empty($value)) {
                        if ($fieldName === 'employees') {
                            // Untuk employees, cek apakah ada yang > 0
                            $hasData = false;
                            foreach ($value as $employee) {
                                if (($employee['craft_labor'] ?? 0) > 0 || 
                                    ($employee['foreman'] ?? 0) > 0 || 
                                    ($employee['supervisor'] ?? 0) > 0 || 
                                    ($employee['manager'] ?? 0) > 0) {
                                    $hasData = true;
                                    break;
                                }
                            }
                            if ($hasData) $filledFields++;
                        } elseif ($fieldName === 'major_projects') {
                            // Untuk major_projects, cek apakah ada project yang terisi
                            $hasData = false;
                            foreach ($value as $project) {
                                if (!empty($project['project_name']) || !empty($project['project_owner'])) {
                                    $hasData = true;
                                    break;
                                }
                            }
                            if ($hasData) $filledFields++;
                        } else {
                            // Untuk facilities dan lainnya
                            if (!empty($value)) $filledFields++;
                        }
                    }
                } elseif ($fieldType === 'enum') {
                    if (!empty($value) && $value !== null) {
                        $filledFields++;
                    }
                } else {
                    // text, textarea, dll
                    if (!empty($value) && $value !== null) {
                        $filledFields++;
                    }
                }
            }
        }
    }

    // 3. TAB DOCUMENTS/ATTACHMENT - Dari tabel vendor_documents
    $requiredDocuments = \App\Models\VendorDocument::where('vendor_id', $this->id)
        ->where('is_required', true)
        ->where('sub_index', 0) // Only count base documents
        ->get();

    foreach ($requiredDocuments as $doc) {
        $totalFields++;
        
        // Dokumen dianggap terisi jika sudah uploaded dan approved
        if (in_array($doc->status, ['uploaded', 'under_review', 'approved'])) {
            $filledFields++;
        }
    }

    // Hitung persentase
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