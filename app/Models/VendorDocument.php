<?php
// app/Models/VendorDocument.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class VendorDocument extends Model
{
    protected $fillable = [
        'vendor_id', 'document_type', 'document_name', 'is_required', 'has_expiry_date',
        'file_name', 'file_path', 'file_size', 'file_type', 'expiry_date', 'expires_soon',
        'status', 'admin_notes', 'rejection_reason', 'reviewed_by', 'reviewed_at', 
        'uploaded_at', 'version'
    ];

    protected $casts = [
        'is_required' => 'boolean',
        'has_expiry_date' => 'boolean',
        'expires_soon' => 'boolean',
        'expiry_date' => 'date',
        'reviewed_at' => 'datetime',
        'uploaded_at' => 'datetime'
    ];

    // Relationships
    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    // Scopes
    public function scopeRequired($query)
    {
        return $query->where('is_required', true);
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeExpiringSoon($query, $days = 30)
    {
        return $query->where('expiry_date', '<=', Carbon::now()->addDays($days))
                    ->where('expiry_date', '>', Carbon::now());
    }

    public function scopeExpired($query)
    {
        return $query->where('expiry_date', '<', Carbon::now());
    }

    // Status helpers
    public function isUploaded()
    {
        return in_array($this->status, ['uploaded', 'under_review', 'approved', 'rejected']);
    }

    public function isApproved()
    {
        return $this->status === 'approved';
    }

    public function isRejected()
    {
        return $this->status === 'rejected';
    }

    public function isPendingReview()
    {
        return in_array($this->status, ['uploaded', 'under_review']);
    }

    public function isExpired()
    {
        return $this->expiry_date && $this->expiry_date < Carbon::now();
    }

    public function isExpiringSoon($days = 30)
    {
        return $this->expiry_date && 
               $this->expiry_date <= Carbon::now()->addDays($days) &&
               $this->expiry_date > Carbon::now();
    }

    // File helpers
    public function getFileUrl()
    {
        return $this->file_path ? asset('storage/' . $this->file_path) : null;
    }

    public function getFormattedFileSize()
    {
        if (!$this->file_size) return '-';
        
        $bytes = (int) $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB'];
        
        for ($i = 0; $bytes > 1024; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }

    // Status badge
    public function getStatusBadge()
    {
        $badges = [
            'not_uploaded' => ['class' => 'secondary', 'icon' => 'fa-minus-circle', 'text' => 'Not Uploaded'],
            'uploaded' => ['class' => 'info', 'icon' => 'fa-clock', 'text' => 'Uploaded'],
            'under_review' => ['class' => 'warning', 'icon' => 'fa-eye', 'text' => 'Under Review'],
            'approved' => ['class' => 'success', 'icon' => 'fa-check-circle', 'text' => 'Approved'],
            'rejected' => ['class' => 'danger', 'icon' => 'fa-times-circle', 'text' => 'Rejected'],
            'expired' => ['class' => 'dark', 'icon' => 'fa-exclamation-triangle', 'text' => 'Expired']
        ];

        return $badges[$this->status] ?? $badges['not_uploaded'];
    }

    // Document types for subcontractor (based on your images)
    public static function getSubcontractorDocumentTypes()
    {
        return [
            // Attachment 1 - Status
            [
                'type' => 'validity_sheet_articles',
                'name' => 'Validity Sheet for Articles of Association',
                'required' => true,
                'has_expiry' => true
            ],
            [
                'type' => 'last_amendment_articles',
                'name' => 'Last Amendment of Articles of Association',
                'required' => false,
                'has_expiry' => false
            ],
            [
                'type' => 'validity_sheet_last_amendment',
                'name' => 'Validity Sheet for Last Amendment of Articles of Association',
                'required' => false,
                'has_expiry' => true
            ],
            [
                'type' => 'audited_financial_year_1',
                'name' => 'Audited Financial Year 1',
                'required' => true,
                'has_expiry' => false
            ],
            [
                'type' => 'audited_financial_year_2',
                'name' => 'Audited Financial Year 2',
                'required' => true,
                'has_expiry' => false
            ],
            [
                'type' => 'audited_financial_year_3',
                'name' => 'Audited Financial Year 3',
                'required' => true,
                'has_expiry' => false
            ],
            [
                'type' => 'nib',
                'name' => 'NIB',
                'required' => true,
                'has_expiry' => true
            ],
            [
                'type' => 'sertifikat_standar',
                'name' => 'Sertifikat Standar',
                'required' => true,
                'has_expiry' => true
            ],
            [
                'type' => 'sbu_konstruksi',
                'name' => 'Sertifikat Badan Usaha (SBU) Konstruksi',
                'required' => true,
                'has_expiry' => true
            ],
            [
                'type' => 'others_siujk',
                'name' => 'Others (SIUJK, SIUP, SIU/PTL, SBU/PTL, dll)',
                'required' => false,
                'has_expiry' => true
            ],
            
            // Continue with other documents...
            [
                'type' => 'checklist_hse',
                'name' => 'Checklist HSE',
                'required' => true,
                'has_expiry' => false
            ],
            [
                'type' => 'drive_hse_checklist',
                'name' => 'Drive HSE Checklist',
                'required' => true,
                'has_expiry' => false
            ],
            
            // Attachment 2 - Status (from your second image)
            [
                'type' => 'catalogue_brochure',
                'name' => 'Catalogue/Brochure',
                'required' => false,
                'has_expiry' => false
            ],
            [
                'type' => 'iso_9001',
                'name' => 'ISO 9001',
                'required' => true,
                'has_expiry' => true
            ],
            [
                'type' => 'iso_14001',
                'name' => 'ISO 14001',
                'required' => false,
                'has_expiry' => true
            ],
            [
                'type' => 'iso_45001',
                'name' => 'ISO 45001',
                'required' => false,
                'has_expiry' => true
            ],
            [
                'type' => 'iso_37001',
                'name' => 'ISO 37001',
                'required' => false,
                'has_expiry' => true
            ],
            [
                'type' => 'others_smk3',
                'name' => 'Others (SMK3, Kebijakan Anti Korupsi/Penyuapan, OHSAS, etc.)',
                'required' => false,
                'has_expiry' => true
            ],
            [
                'type' => 'certificate_association',
                'name' => 'Certificate of Association Membership',
                'required' => false,
                'has_expiry' => true
            ],
            [
                'type' => 'certificate_dpt_pln',
                'name' => 'Certificate of DPT PLN Group',
                'required' => true,
                'has_expiry' => true
            ],
            [
                'type' => 'hse_procedure',
                'name' => 'Copy of HSE Procedure',
                'required' => true,
                'has_expiry' => false
            ],
            [
                'type' => 'cv_manpower_expertise_1',
                'name' => 'Copy of CV Korporasi/Manpower and Certificate of Expertise 1',
                'required' => false,
                'has_expiry' => true
            ],
            [
                'type' => 'cv_manpower_expertise_2',
                'name' => 'Copy of CV Korporasi/Manpower and Certificate of Expertise 2',
                'required' => false,
                'has_expiry' => true
            ],
            [
                'type' => 'cv_manpower_expertise_3',
                'name' => 'Copy of CV Korporasi/Manpower and Certificate of Expertise 3',
                'required' => false,
                'has_expiry' => true
            ],
            [
                'type' => 'sop_document_list',
                'name' => 'List of SOP Document Company',
                'required' => false,
                'has_expiry' => false
            ],
            [
                'type' => 'current_project_list',
                'name' => 'Current Project List (on going)',
                'required' => false,
                'has_expiry' => false
            ],
            [
                'type' => 'equipment_list',
                'name' => 'Equipment List',
                'required' => true,
                'has_expiry' => false
            ],
            [
                'type' => 'manpower_list',
                'name' => 'Manpower List',
                'required' => true,
                'has_expiry' => false
            ],
            [
                'type' => 'quality_management_system',
                'name' => 'Quality Management System/Quality Manual Documents',
                'required' => false,
                'has_expiry' => false
            ]
        ];
    }

    // Initialize documents for a vendor
    public static function initializeForVendor($vendorId, $vendorType = 'SC')
    {
        $documentTypes = [];
        
        if ($vendorType === 'SC') {
            $documentTypes = self::getSubcontractorDocumentTypes();
        }
        
        foreach ($documentTypes as $docType) {
            self::updateOrCreate(
                [
                    'vendor_id' => $vendorId,
                    'document_type' => $docType['type']
                ],
                [
                    'document_name' => $docType['name'],
                    'is_required' => $docType['required'],
                    'has_expiry_date' => $docType['has_expiry'],
                    'status' => 'not_uploaded'
                ]
            );
        }
    }
}