<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class VendorDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'vendor_id',
        'document_type',
        'document_number',
        'parent_type',
        'group',
        'group_name',
        'sub_index',
        'allows_multiple',
        'document_name',
        'document_subtitle',
        'is_required',
        'has_expiry_date',
        'has_template',
        'template_url',
        'template_download_url',
        'template_filename',
        'file_name',
        'file_path',
        'file_size',
        'file_type',
        'expiry_date',
        'expires_soon',
        'status',
        'admin_notes',
        'rejection_reason',
        'reviewed_by',
        'reviewed_at',
        'uploaded_at',
        'version'
    ];

    protected $casts = [
        'is_required' => 'boolean',
        'has_expiry_date' => 'boolean',
        'has_template' => 'boolean',
        'expires_soon' => 'boolean',
        'allows_multiple' => 'boolean',
        'expiry_date' => 'date',
        'reviewed_at' => 'datetime',
        'uploaded_at' => 'datetime',
    ];

    protected $appends = ['file_url'];

    // Relationships
    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function reviewedBy()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    // Initialize documents for a vendor
    public static function initializeForVendor($vendorId, $vendorType = 'SC')
    {
        try {
            $docTypes = [];

            if ($vendorType === 'SC') {
                $docTypes = self::getFlattenedDocumentTypes();
            } else if ($vendorType === 'DS') {
                $docTypes = self::getFlattenedDistributorDocumentTypes(); // ✅ TAMBAH INI
            }

            $createdDocuments = [];

            foreach ($docTypes as $docType) {
                $existingDoc = self::where([
                    'vendor_id' => $vendorId,
                    'document_type' => $docType['type'],
                    'sub_index' => 0
                ])->first();

                if (!$existingDoc) {
                    $createdDoc = self::create([
                        'vendor_id' => $vendorId,
                        'document_type' => $docType['type'],
                        'document_number' => $docType['number'],
                        'document_name' => $docType['name'],
                        'document_subtitle' => $docType['subtitle'] ?? null,
                        'parent_type' => $docType['parent_type'] ?? null,
                        'group' => $docType['group'] ?? null, // ✅ PENTING
                        'group_name' => $docType['group_name'] ?? null, // ✅ PENTING
                        'sub_index' => 0,
                        'allows_multiple' => $docType['allows_multiple'] ?? false,
                        'is_required' => $docType['required'] ?? false,
                        'has_expiry_date' => $docType['has_expiry'] ?? false,
                        'has_template' => $docType['has_template'] ?? false,
                        'template_url' => $docType['template_url'] ?? null,
                        'template_download_url' => $docType['template_download_url'] ?? null,
                        'template_filename' => $docType['template_filename'] ?? null,
                        'status' => 'not_uploaded'
                    ]);
                    $createdDocuments[] = $createdDoc;
                }
            }

            return $createdDocuments;
        } catch (\Exception $e) {
            Log::error('Error initializing vendor documents: ' . $e->getMessage(), [
                'vendor_id' => $vendorId,
                'vendor_type' => $vendorType
            ]);
            return [];
        }
    }

    // Structured document types with hierarchical numbering
    public static function getFlattenedDocumentTypes()
    {
        return [
            // ============= ATTACHMENT 1 - STATUS [TAB] =============

            // 1. Articles of Association and Amendment of Articles of Association (With Validity Sheet)
            [
                'type' => 'articles_association',
                'name' => 'Articles of Association',
                'subtitle' => 'Akta Pendirian',
                'number' => '1.1',
                'required' => true,
                'has_expiry' => false,
                'allows_multiple' => false,
                'parent_type' => 'attachment_1',
                'group' => '1',
                'group_name' => 'Articles of Association and Amendment of Articles of Association (With Validity Sheet)'
            ],

            [
                'type' => 'validity_sheet_articles',
                'name' => 'Validity Sheet for Articles of Association',
                'subtitle' => 'Pengesahan Pendirian',
                'number' => '1.2',
                'required' => true,
                'has_expiry' => true,
                'allows_multiple' => false,
                'parent_type' => 'attachment_1',
                'group' => '1'
            ],

            [
                'type' => 'last_amendment_articles',
                'name' => 'Last Amendment of Articles of Association',
                'subtitle' => 'Akta Perubahan Terakhir',
                'number' => '1.3',
                'required' => false,
                'has_expiry' => false,
                'allows_multiple' => false,
                'parent_type' => 'attachment_1',
                'group' => '1'
            ],

            [
                'type' => 'validity_sheet_last_amendment',
                'name' => 'Validity Sheet for Last Amendment of Articles of Association',
                'subtitle' => 'Pengesahan Perubahan',
                'number' => '1.4',
                'required' => false,
                'has_expiry' => true,
                'allows_multiple' => false,
                'parent_type' => 'attachment_1',
                'group' => '1'
            ],

            // 2. Audited Financial Report (Audited last 3 years)
            [
                'type' => 'audited_financial_year_1',
                'name' => 'Year I',
                'subtitle' => 'Tahun I',
                'number' => '2.1',
                'required' => true,
                'has_expiry' => false,
                'allows_multiple' => false,
                'parent_type' => 'attachment_1',
                'group' => '2',
                'group_name' => 'Audited Financial Report (Audited last 3 years)'
            ],

            [
                'type' => 'audited_financial_year_2',
                'name' => 'Year II',
                'subtitle' => 'Tahun II',
                'number' => '2.2',
                'required' => true,
                'has_expiry' => false,
                'allows_multiple' => false,
                'parent_type' => 'attachment_1',
                'group' => '2'
            ],

            [
                'type' => 'audited_financial_year_3',
                'name' => 'Year III',
                'subtitle' => 'Tahun III',
                'number' => '2.3',
                'required' => true,
                'has_expiry' => false,
                'allows_multiple' => false,
                'parent_type' => 'attachment_1',
                'group' => '2'
            ],

            // 3. Business License
            [
                'type' => 'nib',
                'name' => 'Nomor Induk Berusaha (NIB)',
                'subtitle' => '',
                'number' => '3.1',
                'required' => true,
                'has_expiry' => true,
                'allows_multiple' => false,
                'parent_type' => 'attachment_1',
                'group' => '3',
                'group_name' => 'Business License'
            ],

            [
                'type' => 'sertifikat_standar',
                'name' => 'Sertifikat Standar',
                'subtitle' => '',
                'number' => '3.2',
                'required' => true,
                'has_expiry' => true,
                'allows_multiple' => false,
                'parent_type' => 'attachment_1',
                'group' => '3'
            ],

            [
                'type' => 'sbu_konstruksi',
                'name' => 'Sertifikat Badan Usaha (SBU Konstruksi)',
                'subtitle' => '',
                'number' => '3.3',
                'required' => true,
                'has_expiry' => true,
                'allows_multiple' => false,
                'parent_type' => 'attachment_1',
                'group' => '3'
            ],

            [
                'type' => 'others_business_license',
                'name' => 'Others (SIUJK, SIUP, SIUJPTL, SBUJPTL, dll)',
                'subtitle' => '',
                'number' => '3.4',
                'required' => false,
                'has_expiry' => false,
                'allows_multiple' => true,
                'parent_type' => 'attachment_1',
                'group' => '3'
            ],

            // 4. Check List of Health, Safety and Environment (HSE) & HSE Documents from PLN Group
            [
                'type' => 'checklist_hse',
                'name' => 'Check List of Health, Safety and Environment (HSE)',
                'subtitle' => 'Daftar Periksa Kesehatan, Keselamatan dan Lingkungan',
                'number' => '4.1',
                'required' => true,
                'has_expiry' => false,
                'allows_multiple' => false,
                'parent_type' => 'attachment_1',
                'group' => '4',
                'group_name' => 'Check List of Health, Safety and Environment (HSE) & HSE Documents from PLN Group',
                'has_template' => true,
                'template_url' => 'https://docs.google.com/spreadsheets/d/1TwZ5Vj-p1p91MJX7WYr0oO85a5INLtrI/edit?usp=drive_link&ouid=101184604452390201389&rtpof=true&sd=true',
                'template_download_url' => 'https://docs.google.com/spreadsheets/d/1TwZ5Vj-p1p91MJX7WYr0oO85a5INLtrI/edit?usp=drive_link&ouid=101184604452390201389&rtpof=true&sd=true',
                'template_filename' => 'Template_HSE_Checklist.xlsx'
            ],

            // 5. Company Profile (Last Updated)
            [
                'type' => 'company_profile',
                'name' => 'Company Profile (Last Updated)',
                'subtitle' => 'Profil Perusahaan (Terakhir Diperbarui)',
                'number' => '5',
                'required' => true,
                'has_expiry' => false,
                'allows_multiple' => false,
                'parent_type' => 'attachment_1',
                'group' => '5',
                'group_name' => 'Company Profile (Last Updated)'
            ],

            // 6. Izin Lokasi OSS/PKKPR/Company Domicile Certificate
            [
                'type' => 'izin_lokasi_sppkr',
                'name' => 'Izin Lokasi OSS/PKKPR/Company Domicile Certificate',
                'subtitle' => 'Izin Lokasi OSS/PKKPR/Sertifikat Domisili Perusahaan',
                'number' => '6',
                'required' => true,
                'has_expiry' => true,
                'allows_multiple' => false,
                'parent_type' => 'attachment_1',
                'group' => '6',
                'group_name' => 'Izin Lokasi OSS/PKKPR/Company Domicile Certificate'
            ],

            // 7. Copy of 3 biggest PO/Contract in the last 3-5 years
            [
                'type' => 'po_contract_1',
                'name' => 'Copy of PO/Contract 1',
                'subtitle' => 'Salinan PO/Kontrak 1',
                'number' => '7.1',
                'required' => true,
                'has_expiry' => false,
                'allows_multiple' => false,
                'parent_type' => 'attachment_1',
                'group' => '7',
                'group_name' => 'Copy of 3 biggest PO/Contract in the last 3-5 years'
            ],

            [
                'type' => 'bast_po_contract_1',
                'name' => 'BAST Document from Owner for Copy PO/Contract 1',
                'subtitle' => 'Dokumen BAST dari Owner untuk Salinan PO/Kontrak 1',
                'number' => '7.1.1',
                'required' => true,
                'has_expiry' => false,
                'allows_multiple' => false,
                'parent_type' => 'attachment_1',
                'group' => '7'
            ],

            [
                'type' => 'po_contract_2',
                'name' => 'Copy of PO/Contract 2',
                'subtitle' => 'Salinan PO/Kontrak 2',
                'number' => '7.2',
                'required' => true,
                'has_expiry' => false,
                'allows_multiple' => false,
                'parent_type' => 'attachment_1',
                'group' => '7'
            ],

            [
                'type' => 'bast_po_contract_2',
                'name' => 'BAST Document from Owner for Copy PO/Contract 2',
                'subtitle' => 'Dokumen BAST dari Owner untuk Salinan PO/Kontrak 2',
                'number' => '7.2.1',
                'required' => true,
                'has_expiry' => false,
                'allows_multiple' => false,
                'parent_type' => 'attachment_1',
                'group' => '7'
            ],

            [
                'type' => 'po_contract_3',
                'name' => 'Copy of PO/Contract 3',
                'subtitle' => 'Salinan PO/Kontrak 3',
                'number' => '7.3',
                'required' => true,
                'has_expiry' => false,
                'allows_multiple' => false,
                'parent_type' => 'attachment_1',
                'group' => '7'
            ],

            [
                'type' => 'bast_po_contract_3',
                'name' => 'BAST Document from Owner for Copy PO/Contract 3',
                'subtitle' => 'Dokumen BAST dari Owner untuk Salinan PO/Kontrak 3',
                'number' => '7.3.1',
                'required' => true,
                'has_expiry' => false,
                'allows_multiple' => false,
                'parent_type' => 'attachment_1',
                'group' => '7'
            ],

            // 8. Experience List
            [
                'type' => 'experience_list',
                'name' => 'Experience List',
                'subtitle' => 'Daftar Pengalaman Kerja',
                'number' => '8',
                'required' => true,
                'has_expiry' => false,
                'allows_multiple' => false,
                'parent_type' => 'attachment_1',
                'group' => '8',
                'group_name' => 'Experience List',
                'has_template' => true,
                'template_url' => 'https://docs.google.com/spreadsheets/d/1AGnJmD-BSy85xoypKAQi7ULdFF93FLwb/edit?usp=drive_link&ouid=101184604452390201389&rtpof=true&sd=true',
                'template_download_url' => 'https://docs.google.com/spreadsheets/d/1AGnJmD-BSy85xoypKAQi7ULdFF93FLwb/edit?usp=drive_link&ouid=101184604452390201389&rtpof=true&sd=true',
                'template_filename' => 'Template_Experience_List.xlsx'
            ],

            // 9. Organization Chart
            [
                'type' => 'organization_chart',
                'name' => 'Organization Chart',
                'subtitle' => 'Struktur Organisasi',
                'number' => '9',
                'required' => true,
                'has_expiry' => false,
                'allows_multiple' => false,
                'parent_type' => 'attachment_1',
                'group' => '9',
                'group_name' => 'Organization Chart'
            ],

            // 10. Integrity Pact
            [
                'type' => 'integrity_pact',
                'name' => 'Integrity Pact',
                'subtitle' => 'Pakta Integritas',
                'number' => '10',
                'required' => true,
                'has_expiry' => false,
                'allows_multiple' => false,
                'parent_type' => 'attachment_1',
                'group' => '10',
                'group_name' => 'Integrity Pact',
                'has_template' => true,
                'template_url' => 'https://docs.google.com/document/d/1aRuRLUgxCImm8lFqmoWHIR6MhlP-C3In/edit?usp=drive_link&ouid=101184604452390201389&rtpof=true&sd=true',
                'template_download_url' => 'https://docs.google.com/document/d/1aRuRLUgxCImm8lFqmoWHIR6MhlP-C3In/edit?usp=drive_link&ouid=101184604452390201389&rtpof=true&sd=true',
                'template_filename' => 'Template_Integrity_Pact.docx'
            ],

            // 11. Taxpayer Identification Number
            [
                'type' => 'npwp',
                'name' => 'Nomor Pokok Wajib Pajak (NPWP)',
                'subtitle' => '',
                'number' => '11.1',
                'required' => true,
                'has_expiry' => false,
                'allows_multiple' => false,
                'parent_type' => 'attachment_1',
                'group' => '11',
                'group_name' => 'Taxpayer Identification Number'
            ],

            [
                'type' => 'sppkp',
                'name' => 'Surat Pengukuhan Pengusaha Kena Pajak (SPPKP)',
                'subtitle' => '',
                'number' => '11.2',
                'required' => true,
                'has_expiry' => false,
                'allows_multiple' => false,
                'parent_type' => 'attachment_1',
                'group' => '11'
            ],

            [
                'type' => 'skt',
                'name' => 'Surat Keterangan Terdaftar (SKT)',
                'subtitle' => '',
                'number' => '11.3',
                'required' => true,
                'has_expiry' => false,
                'allows_multiple' => false,
                'parent_type' => 'attachment_1',
                'group' => '11'
            ],

            // 12. Tax Payment Certificate (Yearly) / SPT PPh Tahun Terakhir
            [
                'type' => 'tax_payment_yearly',
                'name' => 'Tax Payment Certificate (Yearly)',
                'subtitle' => 'SPT PPh Tahun Terakhir',
                'number' => '12',
                'required' => true,
                'has_expiry' => false,
                'allows_multiple' => true,
                'parent_type' => 'attachment_1',
                'group' => '12',
                'group_name' => 'Tax Payment Certificate (Yearly) / SPT PPh Tahun Terakhir'
            ],

            // 13. Tax Payment Certificate (Last 3 Months) / SPT PPN 3 Bulan Terakhir
            [
                'type' => 'tax_payment_month_1',
                'name' => 'Tax Payment Certificate Month 1',
                'subtitle' => 'SPT PPN Bulan 1',
                'number' => '13.1',
                'required' => true,
                'has_expiry' => false,
                'allows_multiple' => false,
                'parent_type' => 'attachment_1',
                'group' => '13',
                'group_name' => 'Tax Payment Certificate (Last 3 Months) / SPT PPN 3 Bulan Terakhir'
            ],

            [
                'type' => 'tax_payment_month_2',
                'name' => 'Tax Payment Certificate Month 2',
                'subtitle' => 'SPT PPN Bulan 2',
                'number' => '13.2',
                'required' => true,
                'has_expiry' => false,
                'allows_multiple' => false,
                'parent_type' => 'attachment_1',
                'group' => '13'
            ],

            [
                'type' => 'tax_payment_month_3',
                'name' => 'Tax Payment Certificate Month 3',
                'subtitle' => 'SPT PPN Bulan 3',
                'number' => '13.3',
                'required' => true,
                'has_expiry' => false,
                'allows_multiple' => false,
                'parent_type' => 'attachment_1',
                'group' => '13'
            ],

            // 14. Warehouse and Workshop Information (if any)
            [
                'type' => 'warehouse_workshop',
                'name' => 'Warehouse and Workshop Information (if any)',
                'subtitle' => 'Informasi Warehouse dan Workshop (jika ada)',
                'number' => '14',
                'required' => false,
                'has_expiry' => false,
                'allows_multiple' => false,
                'parent_type' => 'attachment_1',
                'group' => '14',
                'group_name' => 'Warehouse and Workshop Information (if any)',
                'has_template' => true,
                'template_url' => 'https://docs.google.com/document/d/1pw8N5EsDR3OJv20D1CHnQ4q6YWavuEpY/edit?usp=drive_link&ouid=101184604452390201389&rtpof=true&sd=true',
                'template_download_url' => 'https://docs.google.com/document/d/1pw8N5EsDR3OJv20D1CHnQ4q6YWavuEpY/edit?usp=drive_link&ouid=101184604452390201389&rtpof=true&sd=true',
                'template_filename' => 'Template_Warehouse_Workshop.docx'
            ],

            // ============= ATTACHMENT 2 - STATUS [TAB] =============

            // 1. Catalogue/Brochure
            [
                'type' => 'catalogue_brochure',
                'name' => 'Catalogue/Brochure',
                'subtitle' => 'Katalog / Brosur',
                'number' => '1',
                'required' => false,
                'has_expiry' => false,
                'allows_multiple' => true,
                'parent_type' => 'attachment_2',
                'group' => '1',
                'group_name' => 'Catalogue/Brochure'
            ],

            // 2. Certification (ISO, etc) / Sertifikat (ISO, dll)
            [
                'type' => 'iso_9001',
                'name' => 'ISO 9001',
                'subtitle' => '',
                'number' => '2.1',
                'required' => true,
                'has_expiry' => true,
                'allows_multiple' => false,
                'parent_type' => 'attachment_2',
                'group' => '2',
                'group_name' => 'Certification (ISO, etc) / Sertifikat (ISO, dll)'
            ],

            [
                'type' => 'iso_14001',
                'name' => 'ISO 14001',
                'subtitle' => '',
                'number' => '2.2',
                'required' => false,
                'has_expiry' => true,
                'allows_multiple' => false,
                'parent_type' => 'attachment_2',
                'group' => '2'
            ],

            [
                'type' => 'iso_45001',
                'name' => 'ISO 45001',
                'subtitle' => '',
                'number' => '2.3',
                'required' => false,
                'has_expiry' => true,
                'allows_multiple' => false,
                'parent_type' => 'attachment_2',
                'group' => '2'
            ],

            [
                'type' => 'iso_37001',
                'name' => 'ISO 37001',
                'subtitle' => '',
                'number' => '2.4',
                'required' => false,
                'has_expiry' => true,
                'allows_multiple' => false,
                'parent_type' => 'attachment_2',
                'group' => '2'
            ],

            [
                'type' => 'others_certificate_smk3',
                'name' => 'Others Certificate (SMK3, Kebijakan Anti Korupsi/Penyuapan, OHSAS, etc)',
                'subtitle' => 'Siberikan opsi untuk menambahkan dokumen lainnya, lebih dari 1 dokumen/ditambah keterangan dan akses multiple upload',
                'number' => '2.5',
                'required' => false,
                'has_expiry' => true,
                'allows_multiple' => true,
                'parent_type' => 'attachment_2',
                'group' => '2'
            ],

            // 3. Certificate of Association Membership
            [
                'type' => 'association_membership',
                'name' => 'Certificate of Association Membership',
                'subtitle' => 'Sertifikat Keanggotaan Asosiasi',
                'number' => '3',
                'required' => false,
                'has_expiry' => true,
                'allows_multiple' => false,
                'parent_type' => 'attachment_2',
                'group' => '3',
                'group_name' => 'Certificate of Association Membership'
            ],

            // 4. Certificate of Daftar Penyedia Terseleksi (DPT) PLN Group (if any)
            [
                'type' => 'dpt_pln_certificate',
                'name' => 'Certificate of Daftar Penyedia Terseleksi (DPT) PLN Group (if any)',
                'subtitle' => 'Sertifikat Daftar Penyedia Terseleksi (DPT) PLN Group (if any)',
                'number' => '4',
                'required' => false,
                'has_expiry' => true,
                'allows_multiple' => false,
                'parent_type' => 'attachment_2',
                'group' => '4',
                'group_name' => 'Certificate of Daftar Penyedia Terseleksi (DPT) PLN Group (if any)'
            ],

            // 5. Copy of HSE Procedure
            [
                'type' => 'hse_procedure',
                'name' => 'Copy of HSE Procedure',
                'subtitle' => 'Salinan Prosedur dan Kebijakan K3L/HSE',
                'number' => '5',
                'required' => true,
                'has_expiry' => false,
                'allows_multiple' => false,
                'parent_type' => 'attachment_2',
                'group' => '5',
                'group_name' => 'Copy of HSE Procedure'
            ],

            // 6. Copy of CV Keyperson/Manpower dan Certificate of Expertise (min. 3 Keyperson/Manpower)
            [
                'type' => 'cv_keyperson_1',
                'name' => 'Copy of CV Keyperson/Manpower and Certificate of Expertise 1',
                'subtitle' => 'Salinan CV dan Sertifikat Tenaga Ahli 1',
                'number' => '6.1',
                'required' => true,
                'has_expiry' => false,
                'allows_multiple' => false,
                'parent_type' => 'attachment_2',
                'group' => '6',
                'group_name' => 'Copy of CV Keyperson/Manpower dan Certificate of Expertise (min. 3 Keyperson/Manpower)'
            ],

            [
                'type' => 'cv_keyperson_2',
                'name' => 'Copy of CV Keyperson/Manpower and Certificate of Expertise 2',
                'subtitle' => 'Salinan CV dan Sertifikat Tenaga Ahli 2',
                'number' => '6.2',
                'required' => true,
                'has_expiry' => false,
                'allows_multiple' => false,
                'parent_type' => 'attachment_2',
                'group' => '6'
            ],

            [
                'type' => 'cv_keyperson_3',
                'name' => 'Copy of CV Keyperson/Manpower and Certificate of Expertise 3',
                'subtitle' => 'Salinan CV dan Sertifikat Tenaga Ahli 3',
                'number' => '6.3',
                'required' => true,
                'has_expiry' => false,
                'allows_multiple' => false,
                'parent_type' => 'attachment_2',
                'group' => '6'
            ],

            // 7. List of Standard Operating Procedure (SOP) Documents Company
            [
                'type' => 'sop_documents',
                'name' => 'List of Standard Operating Procedure (SOP) Documents Company',
                'subtitle' => 'Daftar Standar Operasional Prosedur (SOP) dan Instruksi Kerja Perusahaan',
                'number' => '7',
                'required' => false,
                'has_expiry' => false,
                'allows_multiple' => false,
                'parent_type' => 'attachment_2',
                'group' => '7',
                'group_name' => 'List of Standard Operating Procedure (SOP) Documents Company'
            ],

            // 8. Current Project List (on going)
            [
                'type' => 'current_project_list',
                'name' => 'Current Project List (on going)',
                'subtitle' => 'Daftar Pekerjaan yang Sedang Berjalan',
                'number' => '8',
                'required' => false,
                'has_expiry' => false,
                'allows_multiple' => false,
                'parent_type' => 'attachment_2',
                'group' => '8',
                'group_name' => 'Current Project List (on going)',
                'has_template' => true,
                'template_url' => 'https://docs.google.com/spreadsheets/d/1HhvQES1GO38EuEK21gpbtyhSED7QGq6m/edit?usp=drive_link&ouid=101184604452390201389&rtpof=true&sd=true',
                'template_download_url' => 'https://docs.google.com/spreadsheets/d/1HhvQES1GO38EuEK21gpbtyhSED7QGq6m/edit?usp=drive_link&ouid=101184604452390201389&rtpof=true&sd=true',
                'template_filename' => 'Template_Current_Project_List.xlsx'
            ],

            // 9. Equipment List
            [
                'type' => 'equipment_list',
                'name' => 'Equipment List',
                'subtitle' => 'Daftar Peralatan Kerja',
                'number' => '9',
                'required' => true,
                'has_expiry' => false,
                'allows_multiple' => false,
                'parent_type' => 'attachment_2',
                'group' => '9',
                'group_name' => 'Equipment List',
                'has_template' => true,
                'template_url' => 'https://docs.google.com/spreadsheets/d/10kWXe8IhsUySU4uqo21x1g9KAgQyPPPA/edit?usp=drive_link&ouid=101184604452390201389&rtpof=true&sd=true',
                'template_download_url' => 'https://docs.google.com/spreadsheets/d/10kWXe8IhsUySU4uqo21x1g9KAgQyPPPA/edit?usp=drive_link&ouid=101184604452390201389&rtpof=true&sd=true',
                'template_filename' => 'Template_Equipment_List.xlsx'
            ],

            // 10. Manpower List
            [
                'type' => 'manpower_list',
                'name' => 'Manpower List',
                'subtitle' => 'Daftar Tenaga Kerja',
                'number' => '10',
                'required' => true,
                'has_expiry' => false,
                'allows_multiple' => false,
                'parent_type' => 'attachment_2',
                'group' => '10',
                'group_name' => 'Manpower List',
                'has_template' => true,
                'template_url' => 'https://docs.google.com/spreadsheets/d/1XILWy_wlgBd3eqd1y2D7tveC_mjUgC_U/edit?usp=drive_link&ouid=101184604452390201389&rtpof=true&sd=true',
                'template_download_url' => 'https://docs.google.com/spreadsheets/d/1XILWy_wlgBd3eqd1y2D7tveC_mjUgC_U/edit?usp=drive_link&ouid=101184604452390201389&rtpof=true&sd=true',
                'template_filename' => 'Template_Manpower_List.xlsx'
            ],

            // 11. Quality Management System/Quality Manual Documents
            [
                'type' => 'quality_management',
                'name' => 'Quality Management System/Quality Manual Documents',
                'subtitle' => '',
                'number' => '11',
                'required' => false,
                'has_expiry' => false,
                'allows_multiple' => false,
                'parent_type' => 'attachment_2',
                'group' => '11',
                'group_name' => 'Quality Management System/Quality Manual Documents'
            ]

            // Tambahkan dokumen lainnya untuk ATTACHMENT 2...
            // (Saya potong untuk menghindari terlalu panjang, silakan lengkapi sendiri)
        ];
    }

    // Rest of methods remain the same...
    public function getFileUrl()
    {
        if (empty($this->file_path)) {
            return null;
        }

        // Jika file_path sudah mengandung storage/vendor_documents/... atau /admin/vendor_documents/..., normalisasi dulu
        $relativePath = $this->file_path;

        // Jika path mengandung /admin/vendor_documents/, ubah ke vendor_documents/
        if (strpos($relativePath, '/admin/vendor_documents/') === 0) {
            $relativePath = str_replace('/admin/vendor_documents/', 'vendor_documents/', $relativePath);
        }
        // Jika path mengandung /storage/vendor_documents/, ubah ke vendor_documents/
        if (strpos($relativePath, '/storage/vendor_documents/') === 0) {
            $relativePath = str_replace('/storage/vendor_documents/', 'vendor_documents/', $relativePath);
        }
        // Hilangkan awalan slash jika ada
        $relativePath = ltrim($relativePath, '/');

        // Gunakan storage:link Laravel (harus sudah dijalankan)
        try {
            return asset('storage/' . $relativePath);
            // atau jika ingin pakai Storage::disk('public')->url():
            // return Storage::disk('public')->url($relativePath);
        } catch (\Exception $e) {
            Log::warning('Storage URL generation failed: ' . $e->getMessage(), [
                'file_path' => $this->file_path,
                'document_id' => $this->id
            ]);
            return asset('storage/' . $relativePath);
        }
    }

    public function getFormattedFileSize()
    {
        if (!$this->file_size) {
            return 'Unknown';
        }

        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        for ($i = 0; $bytes > 1024; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, 2) . ' ' . $units[$i];
    }

    public function isExpired()
    {
        if (!$this->has_expiry_date || !$this->expiry_date) {
            return false;
        }

        return $this->expiry_date->isPast();
    }

    public function isExpiringSoon($days = 30)
    {
        if (!$this->has_expiry_date || !$this->expiry_date) {
            return false;
        }

        return $this->expiry_date->diffInDays(now()) <= $days && $this->expiry_date->isFuture();
    }

    public function getStatusBadge()
    {
        $badges = [
            'not_uploaded' => ['class' => 'secondary', 'icon' => 'fa-upload', 'text' => 'Not Uploaded'],
            'uploaded' => ['class' => 'warning', 'icon' => 'fa-clock', 'text' => 'Uploaded'],
            'under_review' => ['class' => 'info', 'icon' => 'fa-search', 'text' => 'Under Review'],
            'approved' => ['class' => 'success', 'icon' => 'fa-check', 'text' => 'Approved'],
            'rejected' => ['class' => 'danger', 'icon' => 'fa-times-circle', 'text' => 'Rejected'],
            'expired' => ['class' => 'dark', 'icon' => 'fa-exclamation-triangle', 'text' => 'Expired']
        ];

        return $badges[$this->status] ?? $badges['not_uploaded'];
    }

    public function isUploaded()
    {
        return in_array($this->status, ['uploaded', 'under_review', 'approved', 'rejected']);
    }

    public function supportsMultipleFiles()
    {
        return $this->allows_multiple;
    }

    public static function getFlattenedDistributorDocumentTypes()
    {
        return [
            // ============= ATTACHMENT 1 - STATUS [TAB] =============

            // 1. Articles of Association and Amendment of Articles of Association (With Validity Sheet)
            [
                'type' => 'articles_association',
                'name' => 'Articles of Association',
                'subtitle' => 'Akta Pendirian',
                'number' => '1.1',
                'required' => true,
                'has_expiry' => false,
                'allows_multiple' => false,
                'parent_type' => 'attachment_1',
                'group' => '1',
                'group_name' => 'Articles of Association and Amendment of Articles of Association (With Validity Sheet)'
            ],

            [
                'type' => 'validity_sheet_articles',
                'name' => 'Validity Sheet for Articles of Association',
                'subtitle' => 'Pengesahan Pendirian',
                'number' => '1.2',
                'required' => true,
                'has_expiry' => true,
                'allows_multiple' => false,
                'parent_type' => 'attachment_1',
                'group' => '1',
                'group_name' => 'Articles of Association and Amendment of Articles of Association (With Validity Sheet)'
            ],

            [
                'type' => 'last_amendment_articles',
                'name' => 'Last Amendment of Articles of Association',
                'subtitle' => 'Akta Perubahan Terakhir',
                'number' => '1.3',
                'required' => false,
                'has_expiry' => false,
                'allows_multiple' => false,
                'parent_type' => 'attachment_1',
                'group' => '1',
                'group_name' => 'Articles of Association and Amendment of Articles of Association (With Validity Sheet)'
            ],

            [
                'type' => 'validity_sheet_last_amendment',
                'name' => 'Validity Sheet for Last Amendment of Articles of Association',
                'subtitle' => 'Pengesahan Perubahan',
                'number' => '1.4',
                'required' => false,
                'has_expiry' => true,
                'allows_multiple' => false,
                'parent_type' => 'attachment_1',
                'group' => '1',
                'group_name' => 'Articles of Association and Amendment of Articles of Association (With Validity Sheet)'
            ],

            // 2. Audited Financial Report (Audited last 3 years)
            [
                'type' => 'audited_financial_year_1',
                'name' => 'Year I',
                'subtitle' => 'Tahun I',
                'number' => '2.1',
                'required' => true,
                'has_expiry' => false,
                'allows_multiple' => false,
                'parent_type' => 'attachment_1',
                'group' => '2',
                'group_name' => 'Audited Financial Report (Audited last 3 years)'
            ],

            [
                'type' => 'audited_financial_year_2',
                'name' => 'Year II',
                'subtitle' => 'Tahun II',
                'number' => '2.2',
                'required' => true,
                'has_expiry' => false,
                'allows_multiple' => false,
                'parent_type' => 'attachment_1',
                'group' => '2',
                'group_name' => 'Audited Financial Report (Audited last 3 years)'
            ],

            [
                'type' => 'audited_financial_year_3',
                'name' => 'Year III',
                'subtitle' => 'Tahun III',
                'number' => '2.3',
                'required' => true,
                'has_expiry' => false,
                'allows_multiple' => false,
                'parent_type' => 'attachment_1',
                'group' => '2',
                'group_name' => 'Audited Financial Report (Audited last 3 years)'
            ],

            // 3. Business License
            [
                'type' => 'nib',
                'name' => 'Nomor Induk Berusaha (NIB)',
                'subtitle' => '',
                'number' => '3.1',
                'required' => true,
                'has_expiry' => true,
                'allows_multiple' => false,
                'parent_type' => 'attachment_1',
                'group' => '3',
                'group_name' => 'Business License'
            ],

            [
                'type' => 'sertifikat_standar',
                'name' => 'Sertifikat Standar',
                'subtitle' => '',
                'number' => '3.2',
                'required' => true,
                'has_expiry' => true,
                'allows_multiple' => false,
                'parent_type' => 'attachment_1',
                'group' => '3',
                'group_name' => 'Business License'
            ],

            [
                'type' => 'sbu_konstruksi',
                'name' => 'Sertifikat Badan Usaha (SBU Konstruksi)',
                'subtitle' => '',
                'number' => '3.3',
                'required' => true,
                'has_expiry' => true,
                'allows_multiple' => false,
                'parent_type' => 'attachment_1',
                'group' => '3',
                'group_name' => 'Business License'
            ],

            [
                'type' => 'others_business_license',
                'name' => 'Others (SIUJK, SIUP, SIUJPTL, SBUJPTL, dll)',
                'subtitle' => '',
                'number' => '3.4',
                'required' => false,
                'has_expiry' => false,
                'allows_multiple' => true,
                'parent_type' => 'attachment_1',
                'group' => '3',
                'group_name' => 'Business License'
            ],

            // 4. Check List of Health, Safety and Environment (HSE) & HSE Documents from PLN Group
            [
                'type' => 'checklist_hse',
                'name' => 'Check List of Health, Safety and Environment (HSE)',
                'subtitle' => 'Daftar Periksa Kesehatan, Keselamatan dan Lingkungan',
                'number' => '4.1',
                'required' => true,
                'has_expiry' => false,
                'allows_multiple' => false,
                'parent_type' => 'attachment_1',
                'group' => '4',
                'group_name' => 'Check List of Health, Safety and Environment (HSE) & HSE Documents from PLN Group',
                'has_template' => true,
                'template_url' => 'https://docs.google.com/spreadsheets/d/1TwZ5Vj-p1p91MJX7WYr0oO85a5INLtrI/edit?usp=drive_link&ouid=101184604452390201389&rtpof=true&sd=true',
                'template_download_url' => 'https://docs.google.com/spreadsheets/d/1TwZ5Vj-p1p91MJX7WYr0oO85a5INLtrI/edit?usp=drive_link&ouid=101184604452390201389&rtpof=true&sd=true',
                'template_filename' => 'Template_HSE_Checklist.xlsx'
            ],

            // 5. Company Profile (Last Updated)
            [
                'type' => 'company_profile',
                'name' => 'Company Profile (Last Updated)',
                'subtitle' => 'Profil Perusahaan (Terakhir Diperbarui)',
                'number' => '5',
                'required' => true,
                'has_expiry' => false,
                'allows_multiple' => false,
                'parent_type' => 'attachment_1',
                'group' => '5',
                'group_name' => 'Company Profile (Last Updated)'
            ],

            // 6. Izin Lokasi OSS/PKKPR/Company Domicile Certificate
            [
                'type' => 'izin_lokasi_sppkr',
                'name' => 'Izin Lokasi OSS/PKKPR/Company Domicile Certificate',
                'subtitle' => 'Izin Lokasi OSS/PKKPR/Sertifikat Domisili Perusahaan',
                'number' => '6',
                'required' => true,
                'has_expiry' => true,
                'allows_multiple' => false,
                'parent_type' => 'attachment_1',
                'group' => '6',
                'group_name' => 'Izin Lokasi OSS/PKKPR/Company Domicile Certificate'
            ],

            // 7. Copy of 3 biggest PO/Contract in the last 3-5 years
            [
                'type' => 'po_contract_1',
                'name' => 'Copy of PO/Contract 1',
                'subtitle' => 'Salinan PO/Kontrak 1',
                'number' => '7.1',
                'required' => true,
                'has_expiry' => false,
                'allows_multiple' => false,
                'parent_type' => 'attachment_1',
                'group' => '7',
                'group_name' => 'Copy of 3 biggest PO/Contract in the last 3-5 years'
            ],

            [
                'type' => 'bast_po_contract_1',
                'name' => 'BAST Document from Owner for Copy PO/Contract 1',
                'subtitle' => 'Dokumen BAST dari Owner untuk Salinan PO/Kontrak 1',
                'number' => '7.1.1',
                'required' => true,
                'has_expiry' => false,
                'allows_multiple' => false,
                'parent_type' => 'attachment_1',
                'group' => '7',
                'group_name' => 'Copy of 3 biggest PO/Contract in the last 3-5 years'
            ],

            [
                'type' => 'po_contract_2',
                'name' => 'Copy of PO/Contract 2',
                'subtitle' => 'Salinan PO/Kontrak 2',
                'number' => '7.2',
                'required' => true,
                'has_expiry' => false,
                'allows_multiple' => false,
                'parent_type' => 'attachment_1',
                'group' => '7',
                'group_name' => 'Copy of 3 biggest PO/Contract in the last 3-5 years'
            ],

            [
                'type' => 'bast_po_contract_2',
                'name' => 'BAST Document from Owner for Copy PO/Contract 2',
                'subtitle' => 'Dokumen BAST dari Owner untuk Salinan PO/Kontrak 2',
                'number' => '7.2.1',
                'required' => true,
                'has_expiry' => false,
                'allows_multiple' => false,
                'parent_type' => 'attachment_1',
                'group' => '7',
                'group_name' => 'Copy of 3 biggest PO/Contract in the last 3-5 years'
            ],

            [
                'type' => 'po_contract_3',
                'name' => 'Copy of PO/Contract 3',
                'subtitle' => 'Salinan PO/Kontrak 3',
                'number' => '7.3',
                'required' => true,
                'has_expiry' => false,
                'allows_multiple' => false,
                'parent_type' => 'attachment_1',
                'group' => '7',
                'group_name' => 'Copy of 3 biggest PO/Contract in the last 3-5 years'
            ],

            [
                'type' => 'bast_po_contract_3',
                'name' => 'BAST Document from Owner for Copy PO/Contract 3',
                'subtitle' => 'Dokumen BAST dari Owner untuk Salinan PO/Kontrak 3',
                'number' => '7.3.1',
                'required' => true,
                'has_expiry' => false,
                'allows_multiple' => false,
                'parent_type' => 'attachment_1',
                'group' => '7',
                'group_name' => 'Copy of 3 biggest PO/Contract in the last 3-5 years'
            ],

            // 8. Experience List
            [
                'type' => 'experience_list',
                'name' => 'Experience List',
                'subtitle' => 'Daftar Pengalaman Kerja',
                'number' => '8',
                'required' => true,
                'has_expiry' => false,
                'allows_multiple' => false,
                'parent_type' => 'attachment_1',
                'group' => '8',
                'group_name' => 'Experience List',
                'has_template' => true,
                'template_url' => 'https://docs.google.com/spreadsheets/d/1AGnJmD-BSy85xoypKAQi7ULdFF93FLwb/edit?usp=drive_link&ouid=101184604452390201389&rtpof=true&sd=true',
                'template_download_url' => 'https://docs.google.com/spreadsheets/d/1AGnJmD-BSy85xoypKAQi7ULdFF93FLwb/edit?usp=drive_link&ouid=101184604452390201389&rtpof=true&sd=true',
                'template_filename' => 'Template_Experience_List.xlsx'
            ],

            // 9. Organization Chart
            [
                'type' => 'organization_chart',
                'name' => 'Organization Chart',
                'subtitle' => 'Struktur Organisasi',
                'number' => '9',
                'required' => true,
                'has_expiry' => false,
                'allows_multiple' => false,
                'parent_type' => 'attachment_1',
                'group' => '9',
                'group_name' => 'Organization Chart'
            ],

            // 10. Integrity Pact
            [
                'type' => 'integrity_pact',
                'name' => 'Integrity Pact',
                'subtitle' => 'Pakta Integritas',
                'number' => '10',
                'required' => true,
                'has_expiry' => false,
                'allows_multiple' => false,
                'parent_type' => 'attachment_1',
                'group' => '10',
                'group_name' => 'Integrity Pact',
                'has_template' => true,
                'template_url' => 'https://docs.google.com/document/d/1aRuRLUgxCImm8lFqmoWHIR6MhlP-C3In/edit?usp=drive_link&ouid=101184604452390201389&rtpof=true&sd=true',
                'template_download_url' => 'https://docs.google.com/document/d/1aRuRLUgxCImm8lFqmoWHIR6MhlP-C3In/edit?usp=drive_link&ouid=101184604452390201389&rtpof=true&sd=true',
                'template_filename' => 'Template_Integrity_Pact.docx'
            ],

            // 11. Taxpayer Identification Number
            [
                'type' => 'npwp',
                'name' => 'Nomor Pokok Wajib Pajak (NPWP)',
                'subtitle' => '',
                'number' => '11.1',
                'required' => true,
                'has_expiry' => false,
                'allows_multiple' => false,
                'parent_type' => 'attachment_1',
                'group' => '11',
                'group_name' => 'Taxpayer Identification Number'
            ],

            [
                'type' => 'sppkp',
                'name' => 'Surat Pengukuhan Pengusaha Kena Pajak (SPPKP)',
                'subtitle' => '',
                'number' => '11.2',
                'required' => true,
                'has_expiry' => false,
                'allows_multiple' => false,
                'parent_type' => 'attachment_1',
                'group' => '11',
                'group_name' => 'Taxpayer Identification Number'
            ],

            [
                'type' => 'skt',
                'name' => 'Surat Keterangan Terdaftar (SKT)',
                'subtitle' => '',
                'number' => '11.3',
                'required' => true,
                'has_expiry' => false,
                'allows_multiple' => false,
                'parent_type' => 'attachment_1',
                'group' => '11',
                'group_name' => 'Taxpayer Identification Number'
            ],

            // 12. Tax Payment Certificate (Yearly) / SPT PPh Tahun Terakhir
            [
                'type' => 'tax_payment_yearly',
                'name' => 'Tax Payment Certificate (Yearly)',
                'subtitle' => 'SPT PPh Tahun Terakhir',
                'number' => '12',
                'required' => true,
                'has_expiry' => false,
                'allows_multiple' => true,
                'parent_type' => 'attachment_1',
                'group' => '12',
                'group_name' => 'Tax Payment Certificate (Yearly) / SPT PPh Tahun Terakhir'
            ],

            // 13. Tax Payment Certificate (Last 3 Months) / SPT PPN 3 Bulan Terakhir
            [
                'type' => 'tax_payment_month_1',
                'name' => 'Tax Payment Certificate Month 1',
                'subtitle' => 'SPT PPN Bulan 1',
                'number' => '13.1',
                'required' => true,
                'has_expiry' => false,
                'allows_multiple' => false,
                'parent_type' => 'attachment_1',
                'group' => '13',
                'group_name' => 'Tax Payment Certificate (Last 3 Months) / SPT PPN 3 Bulan Terakhir'
            ],

            [
                'type' => 'tax_payment_month_2',
                'name' => 'Tax Payment Certificate Month 2',
                'subtitle' => 'SPT PPN Bulan 2',
                'number' => '13.2',
                'required' => true,
                'has_expiry' => false,
                'allows_multiple' => false,
                'parent_type' => 'attachment_1',
                'group' => '13',
                'group_name' => 'Tax Payment Certificate (Last 3 Months) / SPT PPN 3 Bulan Terakhir'
            ],

            [
                'type' => 'tax_payment_month_3',
                'name' => 'Tax Payment Certificate Month 3',
                'subtitle' => 'SPT PPN Bulan 3',
                'number' => '13.3',
                'required' => true,
                'has_expiry' => false,
                'allows_multiple' => false,
                'parent_type' => 'attachment_1',
                'group' => '13',
                'group_name' => 'Tax Payment Certificate (Last 3 Months) / SPT PPN 3 Bulan Terakhir'
            ],

            // 14. Warehouse and Workshop Information (if any)
            [
                'type' => 'warehouse_workshop',
                'name' => 'Warehouse and Workshop Information (if any)',
                'subtitle' => 'Informasi Warehouse dan Workshop (jika ada)',
                'number' => '14',
                'required' => false,
                'has_expiry' => false,
                'allows_multiple' => false,
                'parent_type' => 'attachment_1',
                'group' => '14',
                'group_name' => 'Warehouse and Workshop Information (if any)',
                'has_template' => true,
                'template_url' => 'https://docs.google.com/document/d/1pw8N5EsDR3OJv20D1CHnQ4q6YWavuEpY/edit?usp=drive_link&ouid=101184604452390201389&rtpof=true&sd=true',
                'template_download_url' => 'https://docs.google.com/document/d/1pw8N5EsDR3OJv20D1CHnQ4q6YWavuEpY/edit?usp=drive_link&ouid=101184604452390201389&rtpof=true&sd=true',
                'template_filename' => 'Template_Warehouse_Workshop.docx'
            ],

            // ============= ATTACHMENT 2 - STATUS [TAB] =============

            // 1. Catalogue/Brochure
            [
                'type' => 'catalogue_brochure',
                'name' => 'Catalogue/Brochure',
                'subtitle' => 'Katalog / Brosur',
                'number' => '1',
                'required' => false,
                'has_expiry' => false,
                'allows_multiple' => true,
                'parent_type' => 'attachment_2',
                'group' => '1',
                'group_name' => 'Catalogue/Brochure'
            ],

            // 2. Certification (ISO, etc) / Sertifikat (ISO, dll)
            [
                'type' => 'iso_9001',
                'name' => 'ISO 9001',
                'subtitle' => '',
                'number' => '2.1',
                'required' => true,
                'has_expiry' => true,
                'allows_multiple' => false,
                'parent_type' => 'attachment_2',
                'group' => '2',
                'group_name' => 'Certification (ISO, etc) / Sertifikat (ISO, dll)'
            ],

            [
                'type' => 'iso_14001',
                'name' => 'ISO 14001',
                'subtitle' => '',
                'number' => '2.2',
                'required' => false,
                'has_expiry' => true,
                'allows_multiple' => false,
                'parent_type' => 'attachment_2',
                'group' => '2',
                'group_name' => 'Certification (ISO, etc) / Sertifikat (ISO, dll)'
            ],

            [
                'type' => 'iso_45001',
                'name' => 'ISO 45001',
                'subtitle' => '',
                'number' => '2.3',
                'required' => false,
                'has_expiry' => true,
                'allows_multiple' => false,
                'parent_type' => 'attachment_2',
                'group' => '2',
                'group_name' => 'Certification (ISO, etc) / Sertifikat (ISO, dll)'
            ],

            [
                'type' => 'iso_37001',
                'name' => 'ISO 37001',
                'subtitle' => '',
                'number' => '2.4',
                'required' => false,
                'has_expiry' => true,
                'allows_multiple' => false,
                'parent_type' => 'attachment_2',
                'group' => '2',
                'group_name' => 'Certification (ISO, etc) / Sertifikat (ISO, dll)'
            ],

            [
                'type' => 'others_certificate_smk3',
                'name' => 'Others Certificate (SMK3, Kebijakan Anti Korupsi/Penyuapan, OHSAS, etc)',
                'subtitle' => 'Siberikan opsi untuk menambahkan dokumen lainnya, lebih dari 1 dokumen/ditambah keterangan dan akses multiple upload',
                'number' => '2.5',
                'required' => false,
                'has_expiry' => true,
                'allows_multiple' => true,
                'parent_type' => 'attachment_2',
                'group' => '2',
                'group_name' => 'Certification (ISO, etc) / Sertifikat (ISO, dll)'
            ],

            // 3. Certificate of Association Membership
            [
                'type' => 'association_membership',
                'name' => 'Certificate of Association Membership',
                'subtitle' => 'Sertifikat Keanggotaan Asosiasi',
                'number' => '3',
                'required' => false,
                'has_expiry' => true,
                'allows_multiple' => false,
                'parent_type' => 'attachment_2',
                'group' => '3',
                'group_name' => 'Certificate of Association Membership'
            ],

            // 4. Certificate of Daftar Penyedia Terseleksi (DPT) PLN Group (if any)
            [
                'type' => 'dpt_pln_certificate',
                'name' => 'Certificate of Daftar Penyedia Terseleksi (DPT) PLN Group (if any)',
                'subtitle' => 'Sertifikat Daftar Penyedia Terseleksi (DPT) PLN Group (if any)',
                'number' => '4',
                'required' => false,
                'has_expiry' => true,
                'allows_multiple' => false,
                'parent_type' => 'attachment_2',
                'group' => '4',
                'group_name' => 'Certificate of Daftar Penyedia Terseleksi (DPT) PLN Group (if any)'
            ],

            // 5. Copy of HSE Procedure
            [
                'type' => 'hse_procedure',
                'name' => 'Copy of HSE Procedure',
                'subtitle' => 'Salinan Prosedur dan Kebijakan K3L/HSE',
                'number' => '5',
                'required' => true,
                'has_expiry' => false,
                'allows_multiple' => false,
                'parent_type' => 'attachment_2',
                'group' => '5',
                'group_name' => 'Copy of HSE Procedure'
            ],

            // 6. Copy of CV Keyperson/Manpower dan Certificate of Expertise (min. 3 Keyperson/Manpower)
            [
                'type' => 'cv_keyperson_1',
                'name' => 'Copy of CV Keyperson/Manpower and Certificate of Expertise 1',
                'subtitle' => 'Salinan CV dan Sertifikat Tenaga Ahli 1',
                'number' => '6.1',
                'required' => true,
                'has_expiry' => false,
                'allows_multiple' => false,
                'parent_type' => 'attachment_2',
                'group' => '6',
                'group_name' => 'Copy of CV Keyperson/Manpower dan Certificate of Expertise (min. 3 Keyperson/Manpower)'
            ],

            [
                'type' => 'cv_keyperson_2',
                'name' => 'Copy of CV Keyperson/Manpower and Certificate of Expertise 2',
                'subtitle' => 'Salinan CV dan Sertifikat Tenaga Ahli 2',
                'number' => '6.2',
                'required' => true,
                'has_expiry' => false,
                'allows_multiple' => false,
                'parent_type' => 'attachment_2',
                'group' => '6',
                'group_name' => 'Copy of CV Keyperson/Manpower dan Certificate of Expertise (min. 3 Keyperson/Manpower)'
            ],

            [
                'type' => 'cv_keyperson_3',
                'name' => 'Copy of CV Keyperson/Manpower and Certificate of Expertise 3',
                'subtitle' => 'Salinan CV dan Sertifikat Tenaga Ahli 3',
                'number' => '6.3',
                'required' => true,
                'has_expiry' => false,
                'allows_multiple' => false,
                'parent_type' => 'attachment_2',
                'group' => '6',
                'group_name' => 'Copy of CV Keyperson/Manpower dan Certificate of Expertise (min. 3 Keyperson/Manpower)'
            ],

            // 7. List of Standard Operating Procedure (SOP) Documents Company
            [
                'type' => 'sop_documents',
                'name' => 'List of Standard Operating Procedure (SOP) Documents Company',
                'subtitle' => 'Daftar Standar Operasional Prosedur (SOP) dan Instruksi Kerja Perusahaan',
                'number' => '7',
                'required' => false,
                'has_expiry' => false,
                'allows_multiple' => false,
                'parent_type' => 'attachment_2',
                'group' => '7',
                'group_name' => 'List of Standard Operating Procedure (SOP) Documents Company'
            ],

            // 8. Current Project List (on going)
            [
                'type' => 'current_project_list',
                'name' => 'Current Project List (on going)',
                'subtitle' => 'Daftar Pekerjaan yang Sedang Berjalan',
                'number' => '8',
                'required' => false,
                'has_expiry' => false,
                'allows_multiple' => false,
                'parent_type' => 'attachment_2',
                'group' => '8',
                'group_name' => 'Current Project List (on going)',
                'has_template' => true,
                'template_url' => 'https://docs.google.com/spreadsheets/d/1HhvQES1GO38EuEK21gpbtyhSED7QGq6m/edit?usp=drive_link&ouid=101184604452390201389&rtpof=true&sd=true',
                'template_download_url' => 'https://docs.google.com/spreadsheets/d/1HhvQES1GO38EuEK21gpbtyhSED7QGq6m/edit?usp=drive_link&ouid=101184604452390201389&rtpof=true&sd=true',
                'template_filename' => 'Template_Current_Project_List.xlsx'
            ],

            // 9. Equipment List
            [
                'type' => 'equipment_list',
                'name' => 'Equipment List',
                'subtitle' => 'Daftar Peralatan Kerja',
                'number' => '9',
                'required' => true,
                'has_expiry' => false,
                'allows_multiple' => false,
                'parent_type' => 'attachment_2',
                'group' => '9',
                'group_name' => 'Equipment List',
                'has_template' => true,
                'template_url' => 'https://docs.google.com/spreadsheets/d/10kWXe8IhsUySU4uqo21x1g9KAgQyPPPA/edit?usp=drive_link&ouid=101184604452390201389&rtpof=true&sd=true',
                'template_download_url' => 'https://docs.google.com/spreadsheets/d/10kWXe8IhsUySU4uqo21x1g9KAgQyPPPA/edit?usp=drive_link&ouid=101184604452390201389&rtpof=true&sd=true',
                'template_filename' => 'Template_Equipment_List.xlsx'
            ],

            // 10. Manpower List
            [
                'type' => 'manpower_list',
                'name' => 'Manpower List',
                'subtitle' => 'Daftar Tenaga Kerja',
                'number' => '10',
                'required' => true,
                'has_expiry' => false,
                'allows_multiple' => false,
                'parent_type' => 'attachment_2',
                'group' => '10',
                'group_name' => 'Manpower List',
                'has_template' => true,
                'template_url' => 'https://docs.google.com/spreadsheets/d/1XILWy_wlgBd3eqd1y2D7tveC_mjUgC_U/edit?usp=drive_link&ouid=101184604452390201389&rtpof=true&sd=true',
                'template_download_url' => 'https://docs.google.com/spreadsheets/d/1XILWy_wlgBd3eqd1y2D7tveC_mjUgC_U/edit?usp=drive_link&ouid=101184604452390201389&rtpof=true&sd=true',
                'template_filename' => 'Template_Manpower_List.xlsx'
            ],

            // 11. Quality Management System/Quality Manual Documents
            [
                'type' => 'quality_management',
                'name' => 'Quality Management System/Quality Manual Documents',
                'subtitle' => '',
                'number' => '11',
                'required' => false,
                'has_expiry' => false,
                'allows_multiple' => false,
                'parent_type' => 'attachment_2',
                'group' => '11',
                'group_name' => 'Quality Management System/Quality Manual Documents'
            ]
        ];
    }

    public static function getFlattenedForwarderDocumentTypes()
    {
        return [
            // ============= ATTACHMENT 1 - STATUS [TAB] =============

            // 1. Articles of Association and Amendment of Articles of Association (With Validity Sheet)
            [
                'type' => 'articles_association',
                'name' => 'Articles of Association',
                'subtitle' => 'Akta Pendirian',
                'number' => '1.1',
                'required' => true,
                'has_expiry' => false,
                'allows_multiple' => false,
                'parent_type' => 'attachment_1',
                'group' => '1',
                'group_name' => 'Articles of Association and Amendment of Articles of Association (With Validity Sheet)'
            ],

            [
                'type' => 'validity_sheet_articles',
                'name' => 'Validity Sheet for Articles of Association',
                'subtitle' => 'Pengesahan Pendirian',
                'number' => '1.2',
                'required' => true,
                'has_expiry' => true,
                'allows_multiple' => false,
                'parent_type' => 'attachment_1',
                'group' => '1',
                'group_name' => 'Articles of Association and Amendment of Articles of Association (With Validity Sheet)'
            ],

            [
                'type' => 'last_amendment_articles',
                'name' => 'Last Amendment of Articles of Association',
                'subtitle' => 'Akta Perubahan Terakhir',
                'number' => '1.3',
                'required' => false,
                'has_expiry' => false,
                'allows_multiple' => false,
                'parent_type' => 'attachment_1',
                'group' => '1',
                'group_name' => 'Articles of Association and Amendment of Articles of Association (With Validity Sheet)'
            ],

            [
                'type' => 'validity_sheet_last_amendment',
                'name' => 'Validity Sheet for Last Amendment of Articles of Association',
                'subtitle' => 'Pengesahan Perubahan',
                'number' => '1.4',
                'required' => false,
                'has_expiry' => true,
                'allows_multiple' => false,
                'parent_type' => 'attachment_1',
                'group' => '1',
                'group_name' => 'Articles of Association and Amendment of Articles of Association (With Validity Sheet)'
            ],

            // 2. Audited Financial Report (Audited last 3 years)
            [
                'type' => 'audited_financial_year_1',
                'name' => 'Year I',
                'subtitle' => 'Tahun I',
                'number' => '2.1',
                'required' => true,
                'has_expiry' => false,
                'allows_multiple' => false,
                'parent_type' => 'attachment_1',
                'group' => '2',
                'group_name' => 'Audited Financial Report (Audited last 3 years)'
            ],

            [
                'type' => 'audited_financial_year_2',
                'name' => 'Year II',
                'subtitle' => 'Tahun II',
                'number' => '2.2',
                'required' => true,
                'has_expiry' => false,
                'allows_multiple' => false,
                'parent_type' => 'attachment_1',
                'group' => '2',
                'group_name' => 'Audited Financial Report (Audited last 3 years)'
            ],

            [
                'type' => 'audited_financial_year_3',
                'name' => 'Year III',
                'subtitle' => 'Tahun III',
                'number' => '2.3',
                'required' => true,
                'has_expiry' => false,
                'allows_multiple' => false,
                'parent_type' => 'attachment_1',
                'group' => '2',
                'group_name' => 'Audited Financial Report (Audited last 3 years)'
            ],

            // 3. Business License
            [
                'type' => 'nib',
                'name' => 'Nomor Induk Berusaha (NIB)',
                'subtitle' => '',
                'number' => '3.1',
                'required' => true,
                'has_expiry' => true,
                'allows_multiple' => false,
                'parent_type' => 'attachment_1',
                'group' => '3',
                'group_name' => 'Business License'
            ],

            [
                'type' => 'sertifikat_standar',
                'name' => 'Sertifikat Standar',
                'subtitle' => '',
                'number' => '3.2',
                'required' => true,
                'has_expiry' => true,
                'allows_multiple' => false,
                'parent_type' => 'attachment_1',
                'group' => '3',
                'group_name' => 'Business License'
            ],

            [
                'type' => 'sbu_konstruksi',
                'name' => 'Sertifikat Badan Usaha (SBU Konstruksi)',
                'subtitle' => '',
                'number' => '3.3',
                'required' => true,
                'has_expiry' => true,
                'allows_multiple' => false,
                'parent_type' => 'attachment_1',
                'group' => '3',
                'group_name' => 'Business License'
            ],

            [
                'type' => 'others_business_license',
                'name' => 'Others (SIUJK, SIUP, SIUJPTL, SBUJPTL, dll)',
                'subtitle' => '',
                'number' => '3.4',
                'required' => false,
                'has_expiry' => false,
                'allows_multiple' => true,
                'parent_type' => 'attachment_1',
                'group' => '3',
                'group_name' => 'Business License'
            ],

            // 4. Company Profile (Last Updated)
            [
                'type' => 'company_profile',
                'name' => 'Company Profile (Last Updated)',
                'subtitle' => 'Profil Perusahaan (Terakhir Diperbarui)',
                'number' => '4',
                'required' => true,
                'has_expiry' => false,
                'allows_multiple' => false,
                'parent_type' => 'attachment_1',
                'group' => '4',
                'group_name' => 'Company Profile (Last Updated)'
            ],

            // 5. Izin Lokasi OSS/PKKPR/Company Domicile Certificate
            [
                'type' => 'izin_lokasi_sppkr',
                'name' => 'Izin Lokasi OSS/PKKPR/Company Domicile Certificate',
                'subtitle' => 'Izin Lokasi OSS/PKKPR/Sertifikat Domisili Perusahaan',
                'number' => '5',
                'required' => true,
                'has_expiry' => true,
                'allows_multiple' => false,
                'parent_type' => 'attachment_1',
                'group' => '5',
                'group_name' => 'Izin Lokasi OSS/PKKPR/Company Domicile Certificate'
            ],

            // 6. Check List of Health, Safety and Environment (HSE) & HSE Documents from PLN Group
            [
                'type' => 'checklist_hse',
                'name' => 'Check List of Health, Safety and Environment (HSE)',
                'subtitle' => 'Daftar Periksa Kesehatan, Keselamatan dan Lingkungan',
                'number' => '6.1',
                'required' => true,
                'has_expiry' => false,
                'allows_multiple' => false,
                'parent_type' => 'attachment_1',
                'group' => '6',
                'group_name' => 'Check List of Health, Safety and Environment (HSE) & HSE Documents from PLN Group',
                'has_template' => true,
                'template_url' => 'https://docs.google.com/spreadsheets/d/1a5eAA3E-xqAZf2LSfIE3o67VQsH1AVWX/edit?usp=drive_link&ouid=101184604452390201389&rtpof=true&sd=true',
                'template_download_url' => 'https://docs.google.com/spreadsheets/d/1a5eAA3E-xqAZf2LSfIE3o67VQsH1AVWX/edit?usp=drive_link&ouid=101184604452390201389&rtpof=true&sd=true',
                'template_filename' => 'Template_HSE_Checklist.xlsx'
            ],

            // 7. Copy of 3 biggest PO/Contract in the last 3-5 years
            [
                'type' => 'po_contract_1',
                'name' => 'Copy of PO/Contract 1',
                'subtitle' => 'Salinan PO/Kontrak 1',
                'number' => '7.1',
                'required' => true,
                'has_expiry' => false,
                'allows_multiple' => false,
                'parent_type' => 'attachment_1',
                'group' => '7',
                'group_name' => 'Copy of 3 biggest PO/Contract in the last 3-5 years'
            ],

            [
                'type' => 'bast_po_contract_1',
                'name' => 'BAST Document from Owner for Copy PO/Contract 1',
                'subtitle' => 'Dokumen BAST dari Owner untuk Salinan PO/Kontrak 1',
                'number' => '7.1.1',
                'required' => true,
                'has_expiry' => false,
                'allows_multiple' => false,
                'parent_type' => 'attachment_1',
                'group' => '7',
                'group_name' => 'Copy of 3 biggest PO/Contract in the last 3-5 years'
            ],

            [
                'type' => 'po_contract_2',
                'name' => 'Copy of PO/Contract 2',
                'subtitle' => 'Salinan PO/Kontrak 2',
                'number' => '7.2',
                'required' => true,
                'has_expiry' => false,
                'allows_multiple' => false,
                'parent_type' => 'attachment_1',
                'group' => '7',
                'group_name' => 'Copy of 3 biggest PO/Contract in the last 3-5 years'
            ],

            [
                'type' => 'bast_po_contract_2',
                'name' => 'BAST Document from Owner for Copy PO/Contract 2',
                'subtitle' => 'Dokumen BAST dari Owner untuk Salinan PO/Kontrak 2',
                'number' => '7.2.1',
                'required' => true,
                'has_expiry' => false,
                'allows_multiple' => false,
                'parent_type' => 'attachment_1',
                'group' => '7',
                'group_name' => 'Copy of 3 biggest PO/Contract in the last 3-5 years'
            ],

            [
                'type' => 'po_contract_3',
                'name' => 'Copy of PO/Contract 3',
                'subtitle' => 'Salinan PO/Kontrak 3',
                'number' => '7.3',
                'required' => true,
                'has_expiry' => false,
                'allows_multiple' => false,
                'parent_type' => 'attachment_1',
                'group' => '7',
                'group_name' => 'Copy of 3 biggest PO/Contract in the last 3-5 years'
            ],

            [
                'type' => 'bast_po_contract_3',
                'name' => 'BAST Document from Owner for Copy PO/Contract 3',
                'subtitle' => 'Dokumen BAST dari Owner untuk Salinan PO/Kontrak 3',
                'number' => '7.3.1',
                'required' => true,
                'has_expiry' => false,
                'allows_multiple' => false,
                'parent_type' => 'attachment_1',
                'group' => '7',
                'group_name' => 'Copy of 3 biggest PO/Contract in the last 3-5 years'
            ],

            // 8. Experience List
            [
                'type' => 'experience_list',
                'name' => 'Experience List',
                'subtitle' => 'Daftar Pengalaman Kerja',
                'number' => '8',
                'required' => true,
                'has_expiry' => false,
                'allows_multiple' => false,
                'parent_type' => 'attachment_1',
                'group' => '8',
                'group_name' => 'Experience List',
                'has_template' => true,
                'template_url' => 'https://docs.google.com/spreadsheets/d/1AGnJmD-BSy85xoypKAQi7ULdFF93FLwb/edit?usp=drive_link&ouid=101184604452390201389&rtpof=true&sd=true',
                'template_download_url' => 'https://docs.google.com/spreadsheets/d/1AGnJmD-BSy85xoypKAQi7ULdFF93FLwb/edit?usp=drive_link&ouid=101184604452390201389&rtpof=true&sd=true',
                'template_filename' => 'Template_Experience_List.xlsx'
            ],

            // 9. Organization Chart
            [
                'type' => 'organization_chart',
                'name' => 'Organization Chart',
                'subtitle' => 'Struktur Organisasi',
                'number' => '9',
                'required' => true,
                'has_expiry' => false,
                'allows_multiple' => false,
                'parent_type' => 'attachment_1',
                'group' => '9',
                'group_name' => 'Organization Chart'
            ],

            // 10. Integrity Pact
            [
                'type' => 'integrity_pact',
                'name' => 'Integrity Pact',
                'subtitle' => 'Pakta Integritas',
                'number' => '10',
                'required' => true,
                'has_expiry' => false,
                'allows_multiple' => false,
                'parent_type' => 'attachment_1',
                'group' => '10',
                'group_name' => 'Integrity Pact',
                'has_template' => true,
                'template_url' => 'https://docs.google.com/document/d/1dZ5DPk52YoLrOf_wulqX7H6SkxA_glxD/edit?usp=drive_link&ouid=101184604452390201389&rtpof=true&sd=true',
                'template_download_url' => 'https://docs.google.com/document/d/1dZ5DPk52YoLrOf_wulqX7H6SkxA_glxD/edit?usp=drive_link&ouid=101184604452390201389&rtpof=true&sd=true',
                'template_filename' => 'Template_Integrity_Pact.docx'
            ],

            // 11. Taxpayer Identification Number
            [
                'type' => 'npwp',
                'name' => 'Nomor Pokok Wajib Pajak (NPWP)',
                'subtitle' => '',
                'number' => '11.1',
                'required' => true,
                'has_expiry' => false,
                'allows_multiple' => false,
                'parent_type' => 'attachment_1',
                'group' => '11',
                'group_name' => 'Taxpayer Identification Number'
            ],

            [
                'type' => 'sppkp',
                'name' => 'Surat Pengukuhan Pengusaha Kena Pajak (SPPKP)',
                'subtitle' => '',
                'number' => '11.2',
                'required' => true,
                'has_expiry' => false,
                'allows_multiple' => false,
                'parent_type' => 'attachment_1',
                'group' => '11',
                'group_name' => 'Taxpayer Identification Number'
            ],

            [
                'type' => 'skt',
                'name' => 'Surat Keterangan Terdaftar (SKT)',
                'subtitle' => '',
                'number' => '11.3',
                'required' => true,
                'has_expiry' => false,
                'allows_multiple' => false,
                'parent_type' => 'attachment_1',
                'group' => '11',
                'group_name' => 'Taxpayer Identification Number'
            ],

            // 12. Tax Payment Certificate (Yearly) / SPT PPh Tahun Terakhir
            [
                'type' => 'tax_payment_yearly',
                'name' => 'Tax Payment Certificate (Yearly)',
                'subtitle' => 'SPT PPh Tahun Terakhir',
                'number' => '12',
                'required' => true,
                'has_expiry' => false,
                'allows_multiple' => true,
                'parent_type' => 'attachment_1',
                'group' => '12',
                'group_name' => 'Tax Payment Certificate (Yearly) / SPT PPh Tahun Terakhir'
            ],

            // 13. Tax Payment Certificate (Last 3 Months) / SPT PPN 3 Bulan Terakhir
            [
                'type' => 'tax_payment_month_1',
                'name' => 'Tax Payment Certificate Month 1',
                'subtitle' => 'SPT PPN Bulan 1',
                'number' => '13.1',
                'required' => true,
                'has_expiry' => false,
                'allows_multiple' => false,
                'parent_type' => 'attachment_1',
                'group' => '13',
                'group_name' => 'Tax Payment Certificate (Last 3 Months) / SPT PPN 3 Bulan Terakhir'
            ],

            [
                'type' => 'tax_payment_month_2',
                'name' => 'Tax Payment Certificate Month 2',
                'subtitle' => 'SPT PPN Bulan 2',
                'number' => '13.2',
                'required' => true,
                'has_expiry' => false,
                'allows_multiple' => false,
                'parent_type' => 'attachment_1',
                'group' => '13',
                'group_name' => 'Tax Payment Certificate (Last 3 Months) / SPT PPN 3 Bulan Terakhir'
            ],

            [
                'type' => 'tax_payment_month_3',
                'name' => 'Tax Payment Certificate Month 3',
                'subtitle' => 'SPT PPN Bulan 3',
                'number' => '13.3',
                'required' => true,
                'has_expiry' => false,
                'allows_multiple' => false,
                'parent_type' => 'attachment_1',
                'group' => '13',
                'group_name' => 'Tax Payment Certificate (Last 3 Months) / SPT PPN 3 Bulan Terakhir'
            ],

            // 14. Warehouse and Workshop Information (if any)
            [
                'type' => 'warehouse_workshop',
                'name' => 'Warehouse and Workshop Information (if any)',
                'subtitle' => 'Informasi Warehouse dan Workshop (jika ada)',
                'number' => '14',
                'required' => false,
                'has_expiry' => false,
                'allows_multiple' => false,
                'parent_type' => 'attachment_1',
                'group' => '14',
                'group_name' => 'Warehouse and Workshop Information (if any)',
                'has_template' => true,
                'template_url' => 'https://docs.google.com/document/d/1pw8N5EsDR3OJv20D1CHnQ4q6YWavuEpY/edit?usp=drive_link&ouid=101184604452390201389&rtpof=true&sd=true',
                'template_download_url' => 'https://docs.google.com/document/d/1pw8N5EsDR3OJv20D1CHnQ4q6YWavuEpY/edit?usp=drive_link&ouid=101184604452390201389&rtpof=true&sd=true',
                'template_filename' => 'Template_Warehouse_Workshop.docx'
            ],

            // ============= ATTACHMENT 2 - STATUS [TAB] =============

            // 1. Articles of Logistic Networking Member
            [
                'type' => 'articel_of_logistic_networking',
                'name' => 'Articel of Logistic Networking',
                'subtitle' => 'Sertifikat Ketenagaahlian Transport (PPJK, Angkat Angkut, Rigger dll)',
                'number' => '1',
                'required' => false,
                'has_expiry' => false,
                'allows_multiple' => false,
                'parent_type' => 'attachment_2',
                'group' => '1',
                'group_name' => 'Article of Logistic Networking'
            ],
            // 2. Catalogue/Brochure
            [
                'type' => 'articel',
                'name' => 'Catalogue/Brochure',
                'subtitle' => 'Katalog / Brosur',
                'number' => '2',
                'required' => false,
                'has_expiry' => false,
                'allows_multiple' => false,
                'parent_type' => 'attachment_2',
                'group' => '2',
                'group_name' => 'Catalogue/Brochure'
            ],

            // 3. Certification (ISO, etc) / Sertifikat (ISO, dll)
            [
                'type' => 'iso_9001',
                'name' => 'ISO 9001',
                'subtitle' => '',
                'number' => '3.1',
                'required' => true,
                'has_expiry' => true,
                'allows_multiple' => false,
                'parent_type' => 'attachment_2',
                'group' => '3',
                'group_name' => 'Certification (ISO, etc) / Sertifikat (ISO, dll)'
            ],

            [
                'type' => 'iso_14001',
                'name' => 'ISO 14001',
                'subtitle' => '',
                'number' => '3.2',
                'required' => false,
                'has_expiry' => true,
                'allows_multiple' => false,
                'parent_type' => 'attachment_2',
                'group' => '3',
                'group_name' => 'Certification (ISO, etc) / Sertifikat (ISO, dll)'
            ],

            [
                'type' => 'iso_45001',
                'name' => 'ISO 45001',
                'subtitle' => '',
                'number' => '3.3',
                'required' => false,
                'has_expiry' => true,
                'allows_multiple' => false,
                'parent_type' => 'attachment_2',
                'group' => '3',
                'group_name' => 'Certification (ISO, etc) / Sertifikat (ISO, dll)'
            ],

            [
                'type' => 'iso_37001',
                'name' => 'ISO 37001',
                'subtitle' => '',
                'number' => '3.4',
                'required' => false,
                'has_expiry' => true,
                'allows_multiple' => false,
                'parent_type' => 'attachment_2',
                'group' => '3',
                'group_name' => 'Certification (ISO, etc) / Sertifikat (ISO, dll)'
            ],

            [
                'type' => 'others_certificate_smk3',
                'name' => 'Others Certificate (SMK3, Kebijakan Anti Korupsi/Penyuapan, OHSAS, etc)',
                'subtitle' => 'Siberikan opsi untuk menambahkan dokumen lainnya, lebih dari 1 dokumen/ditambah keterangan dan akses multiple upload',
                'number' => '3.5',
                'required' => false,
                'has_expiry' => true,
                'allows_multiple' => true,
                'parent_type' => 'attachment_2',
                'group' => '3',
                'group_name' => 'Certification (ISO, etc) / Sertifikat (ISO, dll)'
            ],

            // 4. Certificate of Association Membership
            [
                'type' => 'association_membership',
                'name' => 'Certificate of Association Membership',
                'subtitle' => 'Sertifikat Keanggotaan Asosiasi',
                'number' => '4',
                'required' => false,
                'has_expiry' => true,
                'allows_multiple' => false,
                'parent_type' => 'attachment_2',
                'group' => '4',
                'group_name' => 'Certificate of Association Membership'
            ],

            // 5. Certificate of Daftar Penyedia Terseleksi (DPT) PLN Group (if any)
            [
                'type' => 'dpt_pln_certificate',
                'name' => 'Certificate of Daftar Penyedia Terseleksi (DPT) PLN Group (if any)',
                'subtitle' => 'Sertifikat Daftar Penyedia Terseleksi (DPT) PLN Group (if any)',
                'number' => '5',
                'required' => false,
                'has_expiry' => true,
                'allows_multiple' => false,
                'parent_type' => 'attachment_2',
                'group' => '5',
                'group_name' => 'Certificate of Daftar Penyedia Terseleksi (DPT) PLN Group (if any)'
            ],

            // 6. Copy of CV Keyperson/Manpower dan Certificate of Expertise (min. 3 Keyperson/Manpower)
            [
                'type' => 'cv_keyperson_1',
                'name' => 'Copy of CV Keyperson/Manpower and Certificate of Expertise 1',
                'subtitle' => 'Salinan CV dan Sertifikat Tenaga Ahli 1',
                'number' => '6.1',
                'required' => true,
                'has_expiry' => false,
                'allows_multiple' => false,
                'parent_type' => 'attachment_2',
                'group' => '6',
                'group_name' => 'Copy of CV Keyperson/Manpower dan Certificate of Expertise (min. 3 Keyperson/Manpower)'
            ],

            [
                'type' => 'cv_keyperson_2',
                'name' => 'Copy of CV Keyperson/Manpower and Certificate of Expertise 2',
                'subtitle' => 'Salinan CV dan Sertifikat Tenaga Ahli 2',
                'number' => '6.2',
                'required' => true,
                'has_expiry' => false,
                'allows_multiple' => false,
                'parent_type' => 'attachment_2',
                'group' => '6',
                'group_name' => 'Copy of CV Keyperson/Manpower dan Certificate of Expertise (min. 3 Keyperson/Manpower)'
            ],

            [
                'type' => 'cv_keyperson_3',
                'name' => 'Copy of CV Keyperson/Manpower and Certificate of Expertise 3',
                'subtitle' => 'Salinan CV dan Sertifikat Tenaga Ahli 3',
                'number' => '6.3',
                'required' => true,
                'has_expiry' => false,
                'allows_multiple' => false,
                'parent_type' => 'attachment_2',
                'group' => '6',
                'group_name' => 'Copy of CV Keyperson/Manpower dan Certificate of Expertise (min. 3 Keyperson/Manpower)'
            ],

            // 7. Copy of HSE Procedure
            [
                'type' => 'hse_procedure',
                'name' => 'Copy of HSE Procedure',
                'subtitle' => 'Salinan Prosedur dan Kebijakan K3L/HSE',
                'number' => '7',
                'required' => true,
                'has_expiry' => false,
                'allows_multiple' => false,
                'parent_type' => 'attachment_2',
                'group' => '7',
                'group_name' => 'Copy of HSE Procedure'
            ],

            // 8. Current Project List (on going)
            [
                'type' => 'current_project_list',
                'name' => 'Current Project List (on going)',
                'subtitle' => 'Daftar Pekerjaan yang Sedang Berjalan',
                'number' => '8',
                'required' => false,
                'has_expiry' => false,
                'allows_multiple' => false,
                'parent_type' => 'attachment_2',
                'group' => '8',
                'group_name' => 'Current Project List (on going)',
                'has_template' => true,
                'template_url' => 'https://docs.google.com/spreadsheets/d/1dOVwgGoQgKYKiC6VEjTUzpTieJx98sA3/edit?usp=drive_link&ouid=101184604452390201389&rtpof=true&sd=true',
                'template_download_url' => 'https://docs.google.com/spreadsheets/d/1dOVwgGoQgKYKiC6VEjTUzpTieJx98sA3/edit?usp=drive_link&ouid=101184604452390201389&rtpof=true&sd=true',
                'template_filename' => 'Template_Current_Project_List.xlsx'
            ],

            // 9. Equipment List
            [
                'type' => 'equipment_list',
                'name' => 'Equipment List',
                'subtitle' => 'Daftar Peralatan Kerja',
                'number' => '9',
                'required' => true,
                'has_expiry' => false,
                'allows_multiple' => false,
                'parent_type' => 'attachment_2',
                'group' => '9',
                'group_name' => 'Equipment List',
                'has_template' => true,
                'template_url' => 'https://docs.google.com/spreadsheets/d/1F9NbeJHtDuJEi9_xeQMnziqDPNtP8xr7/edit?usp=drive_link&ouid=101184604452390201389&rtpof=true&sd=true',
                'template_download_url' => 'https://docs.google.com/spreadsheets/d/1F9NbeJHtDuJEi9_xeQMnziqDPNtP8xr7/edit?usp=drive_link&ouid=101184604452390201389&rtpof=true&sd=true',
                'template_filename' => 'Template_Equipment_List.xlsx'
            ],

            // 10. Manpower List
            [
                'type' => 'manpower_list',
                'name' => 'Manpower List',
                'subtitle' => 'Daftar Tenaga Kerja',
                'number' => '10',
                'required' => true,
                'has_expiry' => false,
                'allows_multiple' => false,
                'parent_type' => 'attachment_2',
                'group' => '10',
                'group_name' => 'Manpower List',
                'has_template' => true,
                'template_url' => 'https://docs.google.com/spreadsheets/d/1XILWy_wlgBd3eqd1y2D7tveC_mjUgC_U/edit?usp=drive_link&ouid=101184604452390201389&rtpof=true&sd=true',
                'template_download_url' => 'https://docs.google.com/spreadsheets/d/1XILWy_wlgBd3eqd1y2D7tveC_mjUgC_U/edit?usp=drive_link&ouid=101184604452390201389&rtpof=true&sd=true',
                'template_filename' => 'Template_Manpower_List.xlsx'
            ],

            // 11. Procedure
            [
                'type' => 'shipping_plan',
                'name' => 'Shipping Plan',
                'subtitle' => 'Shipping Plan',
                'number' => '11.1',
                'required' => true,
                'has_expiry' => false,
                'allows_multiple' => false,
                'parent_type' => 'attachment_2',
                'group' => '11',
                'group_name' => 'Procedure'
            ],

            [
                'type' => 'operation_plan',
                'name' => 'Operation Plan',
                'subtitle' => 'Operation Plan',
                'number' => '11.2',
                'required' => true,
                'has_expiry' => false,
                'allows_multiple' => false,
                'parent_type' => 'attachment_2',
                'group' => '11',
                'group_name' => 'Procedure'
            ],

            [
                'type' => 'cv_keyperson_3',
                'name' => 'Project Execution Plan',
                'subtitle' => 'Project Execution Plan',
                'number' => '11.3',
                'required' => true,
                'has_expiry' => false,
                'allows_multiple' => false,
                'parent_type' => 'attachment_2',
                'group' => '11',
                'group_name' => 'Procedure'
            ],

            // 12. Quality Management System/Quality Manual Documents
            [
                'type' => 'quality_management',
                'name' => 'Quality Management System/Quality Manual Documents',
                'subtitle' => '',
                'number' => '12',
                'required' => false,
                'has_expiry' => false,
                'allows_multiple' => false,
                'parent_type' => 'attachment_2',
                'group' => '12',
                'group_name' => 'Quality Management System/Quality Manual Documents'
            ]
        ];
    }

    public static function getFlattenedManufactureDocumentTypes()
    {
        return [
            // ============= ATTACHMENT 1 - STATUS [TAB] =============

            // 1. Articles of Association and Amendment of Articles of Association (With Validity Sheet)
            [
                'type' => 'articles_association',
                'name' => 'Articles of Association',
                'subtitle' => 'Akta Pendirian',
                'number' => '1.1',
                'required' => true,
                'has_expiry' => false,
                'allows_multiple' => false,
                'parent_type' => 'attachment_1',
                'group' => '1',
                'group_name' => 'Articles of Association and Amendment of Articles of Association (With Validity Sheet)'
            ],

            [
                'type' => 'validity_sheet_articles',
                'name' => 'Validity Sheet for Articles of Association',
                'subtitle' => 'Pengesahan Pendirian',
                'number' => '1.2',
                'required' => true,
                'has_expiry' => true,
                'allows_multiple' => false,
                'parent_type' => 'attachment_1',
                'group' => '1',
                'group_name' => 'Articles of Association and Amendment of Articles of Association (With Validity Sheet)'
            ],

            [
                'type' => 'last_amendment_articles',
                'name' => 'Last Amendment of Articles of Association',
                'subtitle' => 'Akta Perubahan Terakhir',
                'number' => '1.3',
                'required' => false,
                'has_expiry' => false,
                'allows_multiple' => false,
                'parent_type' => 'attachment_1',
                'group' => '1',
                'group_name' => 'Articles of Association and Amendment of Articles of Association (With Validity Sheet)'
            ],

            [
                'type' => 'validity_sheet_last_amendment',
                'name' => 'Validity Sheet for Last Amendment of Articles of Association',
                'subtitle' => 'Pengesahan Perubahan',
                'number' => '1.4',
                'required' => false,
                'has_expiry' => true,
                'allows_multiple' => false,
                'parent_type' => 'attachment_1',
                'group' => '1',
                'group_name' => 'Articles of Association and Amendment of Articles of Association (With Validity Sheet)'
            ],

            // 2. Audited Financial Report (Audited last 3 years)
            [
                'type' => 'audited_financial_year_1',
                'name' => 'Year I',
                'subtitle' => 'Tahun I',
                'number' => '2.1',
                'required' => true,
                'has_expiry' => false,
                'allows_multiple' => false,
                'parent_type' => 'attachment_1',
                'group' => '2',
                'group_name' => 'Audited Financial Report (Audited last 3 years)'
            ],

            [
                'type' => 'audited_financial_year_2',
                'name' => 'Year II',
                'subtitle' => 'Tahun II',
                'number' => '2.2',
                'required' => true,
                'has_expiry' => false,
                'allows_multiple' => false,
                'parent_type' => 'attachment_1',
                'group' => '2',
                'group_name' => 'Audited Financial Report (Audited last 3 years)'
            ],

            [
                'type' => 'audited_financial_year_3',
                'name' => 'Year III',
                'subtitle' => 'Tahun III',
                'number' => '2.3',
                'required' => true,
                'has_expiry' => false,
                'allows_multiple' => false,
                'parent_type' => 'attachment_1',
                'group' => '2',
                'group_name' => 'Audited Financial Report (Audited last 3 years)'
            ],

            // 3. Catalogue/Brochure
            [
                'type' => 'catalogue_brochure',
                'name' => 'Catalogue/Brochure',
                'subtitle' => 'Katalog / Brosur',
                'number' => '3',
                'required' => false,
                'has_expiry' => false,
                'allows_multiple' => false,
                'parent_type' => 'attachment_1',
                'group' => '3',
                'group_name' => 'Catalogue/Brochure'
            ],

            // 4. Business License
            [
                'type' => 'nib',
                'name' => 'Nomor Induk Berusaha (NIB)',
                'subtitle' => '',
                'number' => '4.1',
                'required' => true,
                'has_expiry' => true,
                'allows_multiple' => false,
                'parent_type' => 'attachment_1',
                'group' => '4',
                'group_name' => 'Business License'
            ],

            [
                'type' => 'sertifikat_standar',
                'name' => 'Sertifikat Standar',
                'subtitle' => '',
                'number' => '4.2',
                'required' => true,
                'has_expiry' => true,
                'allows_multiple' => false,
                'parent_type' => 'attachment_1',
                'group' => '4',
                'group_name' => 'Business License'
            ],

            [
                'type' => 'sbu_konstruksi',
                'name' => 'Sertifikat Badan Usaha (SBU Konstruksi)',
                'subtitle' => '',
                'number' => '4.3',
                'required' => true,
                'has_expiry' => true,
                'allows_multiple' => false,
                'parent_type' => 'attachment_1',
                'group' => '4',
                'group_name' => 'Business License'
            ],

            [
                'type' => 'others_business_license',
                'name' => 'Others (SIUJK, SIUP, SIUJPTL, SBUJPTL, dll)',
                'subtitle' => '',
                'number' => '4.4',
                'required' => false,
                'has_expiry' => false,
                'allows_multiple' => true,
                'parent_type' => 'attachment_1',
                'group' => '4',
                'group_name' => 'Business License'
            ],

            // 5. Izin Lokasi OSS/PKKPR/Company Domicile Certificate
            [
                'type' => 'izin_lokasi_sppkr',
                'name' => 'Izin Lokasi OSS/PKKPR/Company Domicile Certificate',
                'subtitle' => 'Izin Lokasi OSS/PKKPR/Sertifikat Domisili Perusahaan',
                'number' => '5',
                'required' => true,
                'has_expiry' => true,
                'allows_multiple' => false,
                'parent_type' => 'attachment_1',
                'group' => '5',
                'group_name' => 'Izin Lokasi OSS/PKKPR/Company Domicile Certificate'
            ],

            // 6. Company Profile (Last Updated)
            [
                'type' => 'company_profile',
                'name' => 'Company Profile (Last Updated)',
                'subtitle' => 'Profil Perusahaan (Terakhir Diperbarui)',
                'number' => '6',
                'required' => true,
                'has_expiry' => false,
                'allows_multiple' => false,
                'parent_type' => 'attachment_1',
                'group' => '6',
                'group_name' => 'Company Profile (Last Updated)'
            ],

            // 7. Copy of 3 biggest PO/Contract in the last 3-5 years
            [
                'type' => 'po_contract_1',
                'name' => 'Copy of PO/Contract 1',
                'subtitle' => 'Salinan PO/Kontrak 1',
                'number' => '7.1',
                'required' => true,
                'has_expiry' => false,
                'allows_multiple' => false,
                'parent_type' => 'attachment_1',
                'group' => '7',
                'group_name' => 'Copy of 3 biggest PO/Contract in the last 3-5 years'
            ],

            [
                'type' => 'bast_po_contract_1',
                'name' => 'BAST Document from Owner for Copy PO/Contract 1',
                'subtitle' => 'Dokumen BAST dari Owner untuk Salinan PO/Kontrak 1',
                'number' => '7.1.1',
                'required' => true,
                'has_expiry' => false,
                'allows_multiple' => false,
                'parent_type' => 'attachment_1',
                'group' => '7',
                'group_name' => 'Copy of 3 biggest PO/Contract in the last 3-5 years'
            ],

            [
                'type' => 'po_contract_2',
                'name' => 'Copy of PO/Contract 2',
                'subtitle' => 'Salinan PO/Kontrak 2',
                'number' => '7.2',
                'required' => true,
                'has_expiry' => false,
                'allows_multiple' => false,
                'parent_type' => 'attachment_1',
                'group' => '7',
                'group_name' => 'Copy of 3 biggest PO/Contract in the last 3-5 years'
            ],

            [
                'type' => 'bast_po_contract_2',
                'name' => 'BAST Document from Owner for Copy PO/Contract 2',
                'subtitle' => 'Dokumen BAST dari Owner untuk Salinan PO/Kontrak 2',
                'number' => '7.2.1',
                'required' => true,
                'has_expiry' => false,
                'allows_multiple' => false,
                'parent_type' => 'attachment_1',
                'group' => '7',
                'group_name' => 'Copy of 3 biggest PO/Contract in the last 3-5 years'
            ],

            [
                'type' => 'po_contract_3',
                'name' => 'Copy of PO/Contract 3',
                'subtitle' => 'Salinan PO/Kontrak 3',
                'number' => '7.3',
                'required' => true,
                'has_expiry' => false,
                'allows_multiple' => false,
                'parent_type' => 'attachment_1',
                'group' => '7',
                'group_name' => 'Copy of 3 biggest PO/Contract in the last 3-5 years'
            ],

            [
                'type' => 'bast_po_contract_3',
                'name' => 'BAST Document from Owner for Copy PO/Contract 3',
                'subtitle' => 'Dokumen BAST dari Owner untuk Salinan PO/Kontrak 3',
                'number' => '7.3.1',
                'required' => true,
                'has_expiry' => false,
                'allows_multiple' => false,
                'parent_type' => 'attachment_1',
                'group' => '7',
                'group_name' => 'Copy of 3 biggest PO/Contract in the last 3-5 years'
            ],

            // 8. Experience List
            [
                'type' => 'experience_list',
                'name' => 'Experience List',
                'subtitle' => 'Daftar Pengalaman Kerja',
                'number' => '8',
                'required' => true,
                'has_expiry' => false,
                'allows_multiple' => false,
                'parent_type' => 'attachment_1',
                'group' => '8',
                'group_name' => 'Experience List',
                'has_template' => true,
                'template_url' => 'https://docs.google.com/spreadsheets/d/1AGnJmD-BSy85xoypKAQi7ULdFF93FLwb/edit?usp=drive_link&ouid=101184604452390201389&rtpof=true&sd=true',
                'template_download_url' => 'https://docs.google.com/spreadsheets/d/1AGnJmD-BSy85xoypKAQi7ULdFF93FLwb/edit?usp=drive_link&ouid=101184604452390201389&rtpof=true&sd=true',
                'template_filename' => 'Template_Experience_List.xlsx'
            ],

            // 9. Organization Chart
            [
                'type' => 'organization_chart',
                'name' => 'Organization Chart',
                'subtitle' => 'Struktur Organisasi',
                'number' => '9',
                'required' => true,
                'has_expiry' => false,
                'allows_multiple' => false,
                'parent_type' => 'attachment_1',
                'group' => '9',
                'group_name' => 'Organization Chart'
            ],

            // 10. Integrity Pact
            [
                'type' => 'integrity_pact',
                'name' => 'Integrity Pact',
                'subtitle' => 'Pakta Integritas',
                'number' => '10',
                'required' => true,
                'has_expiry' => false,
                'allows_multiple' => false,
                'parent_type' => 'attachment_1',
                'group' => '10',
                'group_name' => 'Integrity Pact',
                'has_template' => true,
                'template_url' => 'https://docs.google.com/document/d/1c4oWoybW74B6MBArjnP36QaH9I5eMhtB/edit?usp=drive_link&ouid=101184604452390201389&rtpof=true&sd=true',
                'template_download_url' => 'https://docs.google.com/document/d/1c4oWoybW74B6MBArjnP36QaH9I5eMhtB/edit?usp=drive_link&ouid=101184604452390201389&rtpof=true&sd=true',
                'template_filename' => 'Template_Integrity_Pact.docx'
            ],

            // 11. Taxpayer Identification Number
            [
                'type' => 'npwp',
                'name' => 'Nomor Pokok Wajib Pajak (NPWP)',
                'subtitle' => '',
                'number' => '11.1',
                'required' => true,
                'has_expiry' => false,
                'allows_multiple' => false,
                'parent_type' => 'attachment_1',
                'group' => '11',
                'group_name' => 'Taxpayer Identification Number'
            ],

            [
                'type' => 'sppkp',
                'name' => 'Surat Pengukuhan Pengusaha Kena Pajak (SPPKP)',
                'subtitle' => '',
                'number' => '11.2',
                'required' => true,
                'has_expiry' => false,
                'allows_multiple' => false,
                'parent_type' => 'attachment_1',
                'group' => '11',
                'group_name' => 'Taxpayer Identification Number'
            ],

            [
                'type' => 'skt',
                'name' => 'Surat Keterangan Terdaftar (SKT)',
                'subtitle' => '',
                'number' => '11.3',
                'required' => true,
                'has_expiry' => false,
                'allows_multiple' => false,
                'parent_type' => 'attachment_1',
                'group' => '11',
                'group_name' => 'Taxpayer Identification Number'
            ],

            // 12. Tax Payment Certificate (Yearly) / SPT PPh Tahun Terakhir
            [
                'type' => 'tax_payment_yearly',
                'name' => 'Tax Payment Certificate (Yearly)',
                'subtitle' => 'SPT PPh Tahun Terakhir',
                'number' => '12',
                'required' => true,
                'has_expiry' => false,
                'allows_multiple' => false,
                'parent_type' => 'attachment_1',
                'group' => '12',
                'group_name' => 'Tax Payment Certificate (Yearly) / SPT PPh Tahun Terakhir'
            ],

            // 13. Tax Payment Certificate (Last 3 Months) / SPT PPN 3 Bulan Terakhir
            [
                'type' => 'tax_payment_month_1',
                'name' => 'Tax Payment Certificate Month 1',
                'subtitle' => 'SPT PPN Bulan 1',
                'number' => '13.1',
                'required' => true,
                'has_expiry' => false,
                'allows_multiple' => false,
                'parent_type' => 'attachment_1',
                'group' => '13',
                'group_name' => 'Tax Payment Certificate (Last 3 Months) / SPT PPN 3 Bulan Terakhir'
            ],

            [
                'type' => 'tax_payment_month_2',
                'name' => 'Tax Payment Certificate Month 2',
                'subtitle' => 'SPT PPN Bulan 2',
                'number' => '13.2',
                'required' => true,
                'has_expiry' => false,
                'allows_multiple' => false,
                'parent_type' => 'attachment_1',
                'group' => '13',
                'group_name' => 'Tax Payment Certificate (Last 3 Months) / SPT PPN 3 Bulan Terakhir'
            ],

            [
                'type' => 'tax_payment_month_3',
                'name' => 'Tax Payment Certificate Month 3',
                'subtitle' => 'SPT PPN Bulan 3',
                'number' => '13.3',
                'required' => true,
                'has_expiry' => false,
                'allows_multiple' => false,
                'parent_type' => 'attachment_1',
                'group' => '13',
                'group_name' => 'Tax Payment Certificate (Last 3 Months) / SPT PPN 3 Bulan Terakhir'
            ],

            // 14. Warehouse and Workshop Information (if any)
            [
                'type' => 'warehouse_workshop',
                'name' => 'Warehouse and Workshop Information (if any)',
                'subtitle' => 'Informasi Warehouse dan Workshop (jika ada)',
                'number' => '14',
                'required' => false,
                'has_expiry' => false,
                'allows_multiple' => false,
                'parent_type' => 'attachment_1',
                'group' => '14',
                'group_name' => 'Warehouse and Workshop Information (if any)',
                'has_template' => true,
                'template_url' => 'https://docs.google.com/document/d/1pw8N5EsDR3OJv20D1CHnQ4q6YWavuEpY/edit?usp=drive_link&ouid=101184604452390201389&rtpof=true&sd=true',
                'template_download_url' => 'https://docs.google.com/document/d/1pw8N5EsDR3OJv20D1CHnQ4q6YWavuEpY/edit?usp=drive_link&ouid=101184604452390201389&rtpof=true&sd=true',
                'template_filename' => 'Template_Warehouse_Workshop.docx'
            ],

            // 15. Surat Pernyataan Self-Assessment atau Sertifikat TKDN 
            [
                'type' => 'tkdn',
                'name' => 'Self-Assessment Statement Letter or TKDN Certificate',
                'subtitle' => 'Surat Pernyataan Self-Assessment atau Sertifikat TKDN',
                'number' => '15',
                'required' => false,
                'has_expiry' => false,
                'allows_multiple' => false,
                'parent_type' => 'attachment_1',
                'group' => '15',
                'group_name' => 'Self-Assessment Statement Letter or TKDN Certificate'
            ],

            // ============= ATTACHMENT 2 - STATUS [TAB] =============

            // 1. Certificate of Association Membership
            [
                'type' => 'association_membership',
                'name' => 'Certificate of Association Membership',
                'subtitle' => 'Sertifikat Keanggotaan Asosiasi',
                'number' => '1',
                'required' => false,
                'has_expiry' => true,
                'allows_multiple' => false,
                'parent_type' => 'attachment_2',
                'group' => '1',
                'group_name' => 'Certificate of Association Membership'
            ],

            // 2. Certification (ISO, etc) / Sertifikat (ISO, dll)
            [
                'type' => 'iso_9001',
                'name' => 'ISO 9001',
                'subtitle' => '',
                'number' => '2.1',
                'required' => true,
                'has_expiry' => true,
                'allows_multiple' => false,
                'parent_type' => 'attachment_2',
                'group' => '2',
                'group_name' => 'Certification (ISO, etc) / Sertifikat (ISO, dll)'
            ],

            [
                'type' => 'iso_14001',
                'name' => 'ISO 14001',
                'subtitle' => '',
                'number' => '2.2',
                'required' => false,
                'has_expiry' => true,
                'allows_multiple' => false,
                'parent_type' => 'attachment_2',
                'group' => '2',
                'group_name' => 'Certification (ISO, etc) / Sertifikat (ISO, dll)'
            ],

            [
                'type' => 'iso_45001',
                'name' => 'ISO 45001',
                'subtitle' => '',
                'number' => '2.3',
                'required' => false,
                'has_expiry' => true,
                'allows_multiple' => false,
                'parent_type' => 'attachment_2',
                'group' => '2',
                'group_name' => 'Certification (ISO, etc) / Sertifikat (ISO, dll)'
            ],

            [
                'type' => 'iso_37001',
                'name' => 'ISO 37001',
                'subtitle' => '',
                'number' => '2.4',
                'required' => false,
                'has_expiry' => true,
                'allows_multiple' => false,
                'parent_type' => 'attachment_2',
                'group' => '2',
                'group_name' => 'Certification (ISO, etc) / Sertifikat (ISO, dll)'
            ],

            [
                'type' => 'others_certificate_smk3',
                'name' => 'Others Certificate (SMK3, Kebijakan Anti Korupsi/Penyuapan, OHSAS, etc)',
                'subtitle' => 'Siberikan opsi untuk menambahkan dokumen lainnya, lebih dari 1 dokumen/ditambah keterangan dan akses multiple upload',
                'number' => '2.5',
                'required' => false,
                'has_expiry' => false,
                'allows_multiple' => true,
                'parent_type' => 'attachment_2',
                'group' => '2',
                'group_name' => 'Certification (ISO, etc) / Sertifikat (ISO, dll)'
            ],

            // 3. Certificate of Daftar Penyedia Terseleksi (DPT) PLN Group (if any)
            [
                'type' => 'dpt_pln_certificate',
                'name' => 'Certificate of Daftar Penyedia Terseleksi (DPT) PLN Group (if any)',
                'subtitle' => 'Sertifikat Daftar Penyedia Terseleksi (DPT) PLN Group (if any)',
                'number' => '3',
                'required' => false,
                'has_expiry' => true,
                'allows_multiple' => false,
                'parent_type' => 'attachment_2',
                'group' => '3',
                'group_name' => 'Certificate of Daftar Penyedia Terseleksi (DPT) PLN Group (if any)'
            ],

            // 4. Current Project List (on going)
            [
                'type' => 'current_project_list',
                'name' => 'Current Project List (on going)',
                'subtitle' => 'Daftar Pekerjaan yang Sedang Berjalan',
                'number' => '4',
                'required' => false,
                'has_expiry' => false,
                'allows_multiple' => false,
                'parent_type' => 'attachment_2',
                'group' => '4',
                'group_name' => 'Current Project List (on going)',
                'has_template' => true,
                'template_url' => 'https://docs.google.com/spreadsheets/d/1HhvQES1GO38EuEK21gpbtyhSED7QGq6m/edit?usp=drive_link&ouid=101184604452390201389&rtpof=true&sd=true',
                'template_download_url' => 'https://docs.google.com/spreadsheets/d/1HhvQES1GO38EuEK21gpbtyhSED7QGq6m/edit?usp=drive_link&ouid=101184604452390201389&rtpof=true&sd=true',
                'template_filename' => 'Template_Current_Project_List.xlsx'
            ],

            // 5. Equipment List
            [
                'type' => 'equipment_list',
                'name' => 'Equipment List',
                'subtitle' => 'Daftar Peralatan Kerja',
                'number' => '5',
                'required' => true,
                'has_expiry' => false,
                'allows_multiple' => false,
                'parent_type' => 'attachment_2',
                'group' => '5',
                'group_name' => 'Equipment List',
                'has_template' => true,
                'template_url' => 'https://docs.google.com/spreadsheets/d/10kWXe8IhsUySU4uqo21x1g9KAgQyPPPA/edit?usp=drive_link&ouid=101184604452390201389&rtpof=true&sd=true',
                'template_download_url' => 'https://docs.google.com/spreadsheets/d/10kWXe8IhsUySU4uqo21x1g9KAgQyPPPA/edit?usp=drive_link&ouid=101184604452390201389&rtpof=true&sd=true',
                'template_filename' => 'Template_Equipment_List.xlsx'
            ],

            // 6. Inspection and Test Procedure (ITP)
            [
                'type' => 'inspection_test_procedure',
                'name' => 'Inspection and Test Procedure (ITP)',
                'subtitle' => 'Prosedur Inspeksi dan Pengujian (ITP)',
                'number' => '6',
                'required' => false,
                'has_expiry' => false,
                'allows_multiple' => false,
                'parent_type' => 'attachment_2',
                'group' => '6',
                'group_name' => 'Equipment List',
            ],

            // 7. List of Standard Operating Procedure (SOP) Documents Company
            [
                'type' => 'sop_documents',
                'name' => 'List of Standard Operating Procedure (SOP) Documents Company',
                'subtitle' => 'Daftar Standar Operasional Prosedur (SOP) dan Instruksi Kerja Perusahaan',
                'number' => '7',
                'required' => false,
                'has_expiry' => false,
                'allows_multiple' => false,
                'parent_type' => 'attachment_2',
                'group' => '7',
                'group_name' => 'List of Standard Operating Procedure (SOP) Documents Company'
            ],

            // 8. Manpower List
            [
                'type' => 'manpower_list',
                'name' => 'Manpower List',
                'subtitle' => 'Daftar Tenaga Kerja',
                'number' => '8',
                'required' => true,
                'has_expiry' => false,
                'allows_multiple' => false,
                'parent_type' => 'attachment_2',
                'group' => '8',
                'group_name' => 'Manpower List',
                'has_template' => true,
                'template_url' => 'https://docs.google.com/spreadsheets/d/1WyOWzyZKFHSJx2PDSBbrW4OpZ0LT2ceu/edit?usp=drive_link&ouid=101184604452390201389&rtpof=true&sd=true',
                'template_download_url' => 'https://docs.google.com/spreadsheets/d/1WyOWzyZKFHSJx2PDSBbrW4OpZ0LT2ceu/edit?usp=drive_link&ouid=101184604452390201389&rtpof=true&sd=true',
                'template_filename' => 'Template_Manpower_List.xlsx'
            ],

            // 9. Manual Book of Product (if any)
            [
                'type' => 'manual_book_product',
                'name' => 'Manual Book of Product (if any)',
                'subtitle' => 'Buku Manual Produk (jika ada)',
                'number' => '9',
                'required' => false,
                'has_expiry' => false,
                'allows_multiple' => false,
                'parent_type' => 'attachment_2',
                'group' => '9',
                'group_name' => 'Manual Book of Product (if any)'
            ],
        ];
    }

    // ✅ TAMBAH: Method untuk get document templates tanpa create
    public static function getDocumentTemplatesForVendor($vendorType = 'SC')
    {
        $docTypes = [];

        if ($vendorType === 'SC') {
            $docTypes = self::getFlattenedDocumentTypes();
        } else if ($vendorType === 'DS') {
            $docTypes = self::getFlattenedDistributorDocumentTypes();
        } else if ($vendorType === 'FW') {
            $docTypes = self::getFlattenedForwarderDocumentTypes();
        } else if ($vendorType === 'MF') {
            $docTypes = self::getFlattenedManufactureDocumentTypes();
        }
        return $docTypes; // <== tambah ini
    }

    // ✅ UBAH: Method untuk create document saat upload saja
    public static function createDocumentOnUpload($vendorId, $documentType, $vendorType)
    {
        try {
            // Get document template
            $templates = self::getDocumentTemplatesForVendor($vendorType);
            $template = collect($templates)->firstWhere('type', $documentType);

            if (!$template) {
                throw new \Exception("Document template not found for type: $documentType");
            }

            // Check if document already exists
            $existingDoc = self::where([
                'vendor_id' => $vendorId,
                'document_type' => $documentType,
                'sub_index' => 0
            ])->first();

            if ($existingDoc) {
                return $existingDoc;
            }

            // Create new document
            return self::create([
                'vendor_id' => $vendorId,
                'document_type' => $template['type'],
                'document_number' => $template['number'],
                'document_name' => $template['name'],
                'document_subtitle' => $template['subtitle'] ?? null,
                'parent_type' => $template['parent_type'] ?? null,
                'group' => $template['group'] ?? null,
                'group_name' => $template['group_name'] ?? null,
                'sub_index' => 0,
                'allows_multiple' => $template['allows_multiple'] ?? false,
                'is_required' => $template['required'] ?? false,
                'has_expiry_date' => $template['has_expiry'] ?? false,
                'has_template' => $template['has_template'] ?? false,
                'template_url' => $template['template_url'] ?? null,
                'template_download_url' => $template['template_download_url'] ?? null,
                'template_filename' => $template['template_filename'] ?? null,
                'status' => 'not_uploaded'
            ]);
        } catch (\Exception $e) {
            Log::error('Error creating document on upload: ' . $e->getMessage(), [
                'vendor_id' => $vendorId,
                'document_type' => $documentType,
                'vendor_type' => $vendorType
            ]);
            throw $e;
        }
    }

    public function getFileUrlAttribute()
{
    return $this->getFileUrl();
}
}