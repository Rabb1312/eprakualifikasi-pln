<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use App\Models\User;
use App\Models\Subcontractor;
use App\Models\VendorDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response; 
use Illuminate\Http\UploadedFile;


class VendorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        $user = $this->authenticateToken($request);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 401);
        }

        $vendor = $user->vendor;

        if (!$vendor) {
            $vendor = $this->createVendorProfile($user);
            
            if (!$vendor) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to create vendor profile'
                ], 500);
            }
        }

        return response()->json([
            'success' => true,
            'data' => [
                'vendor' => $vendor
            ]
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Vendor $Vendor)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
public function update(Request $request)
{
    $user = $this->authenticateToken($request);

    if (!$user) {
        return response()->json([
            'success' => false,
            'message' => 'Unauthorized'
        ], 401);
    }

    $vendor = $user->vendor;

    if (!$vendor) {
        return response()->json([
            'success' => false,
            'message' => 'Vendor profile not found'
        ], 404);
    }

    try {
        $updateData = $request->all();
        
        // ✅ Remove readonly fields dari update
        $readonlyFields = [
            'id', 'user_id', 'created_at', 'updated_at',
            'nomor_vendor', 'tipe_perusahaan', 'nama_perusahaan',
            'sumber_informasi', 'rekomendasi_dari', 'lainnya' // ✅ ADDED: Prevent update
        ];
        
        foreach ($readonlyFields as $field) {
            unset($updateData[$field]);
        }
        
        Log::info('Updating vendor profile', [
            'vendor_id' => $vendor->id,
            'update_data' => $updateData
        ]);
        
        // Update vendor data
        $vendor->update($updateData);
        
        // Recalculate completion
        if (method_exists($vendor, 'calculateCompletion')) {
            $vendor->calculateCompletion();
        }
        
        $vendor = $vendor->fresh();

        return response()->json([
            'success' => true,
            'message' => 'Profil vendor berhasil diperbarui',
            'data' => [
                'vendor' => $vendor->toArray() // ✅ Return complete vendor data
            ]
        ]);

    } catch (\Exception $e) {
        Log::error('Update vendor profile error: ' . $e->getMessage(), [
            'vendor_id' => $vendor->id,
            'trace' => $e->getTraceAsString()
        ]);
        
        return response()->json([
            'success' => false,
            'message' => 'Gagal memperbarui profil vendor: ' . $e->getMessage()
        ], 500);
    }
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Vendor $Vendor)
    {
        //
    }

    public function getFieldMappings(Request $request)
    {
        $user = $this->authenticateToken($request);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 401);
        }

        $vendor = $user->vendor;
        if (!$vendor) {
            // Create vendor profile if not exists
            $vendor = $this->createVendorProfile($user);
            
            if (!$vendor) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to create vendor profile'
                ], 500);
            }
        }

        $fieldDefinitions = $this->getFieldDefinitions();
        $vendorFields = $this->getVendorTypeFields($vendor->tipe_perusahaan);

        $mappedFields = [];
        foreach ($vendorFields as $fieldNum) {
            if (isset($fieldDefinitions[$fieldNum])) {
                $mappedFields[] = $fieldDefinitions[$fieldNum];
            }
        }

        return response()->json([
            'success' => true,
            'data' => [
                'vendor_type' => $vendor->tipe_perusahaan,
                'vendor_name' => $this->getVendorTypeName($vendor->tipe_perusahaan),
                'fields' => $mappedFields,
                'total_fields' => count($mappedFields),
                'vendor_config' => $this->getVendorTypeConfig($vendor->tipe_perusahaan)
            ]
        ]);
    }

    private function authenticateToken(Request $request)
    {
        $token = $request->bearerToken();

        if (!$token) {
            Log::warning('No bearer token provided');
            return null;
        }

        try {
            $decoded = base64_decode($token);
            $parts = explode('|', $decoded);

            if (count($parts) < 3) {
                Log::warning('Invalid token format - expected 3 parts, got ' . count($parts));
                return null;
            }

            $userId = $parts[0];
            $timestamp = $parts[2];

            // Check if token is not too old (24 hours)
            if (time() - $timestamp > 86400) {
                Log::warning('Token expired for user ID: ' . $userId);
                return null;
            }

            $user = User::find($userId);

            if (!$user) {
                Log::warning('User not found for ID: ' . $userId);
                return null;
            }

            if (!$user->isActive()) {
                Log::warning('User is not active: ' . $userId);
                return null;
            }

            Log::info('User authenticated successfully: ' . $user->id);
            return $user;

        } catch (\Exception $e) {
            Log::error('Token authentication error: ' . $e->getMessage());
            return null;
        }
    }

    private function createVendorProfile($user)
{
    try {
        // Check if vendor already exists first
        $existingVendor = Vendor::where('user_id', $user->id)->first();
        if ($existingVendor) {
            Log::info('Vendor already exists for user: ' . $user->id);
            return $existingVendor;
        }

        // Generate nomor vendor
        $nomorVendor = Vendor::generateNomorVendor($user->type);

        // ✅ FIXED: Get original registration data dari vendor yang mungkin sudah ada
        // atau dari data registrasi yang tersimpan
        $registrationData = $this->getRegistrationData($user);

        // Create vendor profile dengan data asli dari registrasi
        $vendor = Vendor::create([
            'user_id' => $user->id,
            'nomor_vendor' => $nomorVendor,
            'tipe_perusahaan' => $user->type,
            'nama_perusahaan' => $user->fullname,
            'npwp' => $registrationData['npwp'] ?? null,
            'sumber_informasi' => $registrationData['sumber_informasi'] ?? 'website',
            'rekomendasi_dari' => $registrationData['rekomendasi_dari'] ?? null,
            'lainnya' => $registrationData['lainnya'] ?? null,
            'profile_completed' => false,
            'completion_percentage' => 0
        ]);

        // Calculate initial completion
        if (method_exists($vendor, 'calculateCompletion')) {
            $vendor->calculateCompletion();
        }

        Log::info('Vendor profile created successfully for user: ' . $user->id);
        return $vendor;

    } catch (\Exception $e) {
        Log::error('Create vendor profile error: ' . $e->getMessage());
        Log::error('Stack trace: ' . $e->getTraceAsString());
        return null;
    }
}

private function getRegistrationData($user)
{
    // First, check if vendor data already exists in database
    $existingVendor = Vendor::withTrashed()->where('user_id', $user->id)->first();
    
    if ($existingVendor) {
        return [
            'npwp' => $existingVendor->npwp,
            'sumber_informasi' => $existingVendor->sumber_informasi,
            'rekomendasi_dari' => $existingVendor->rekomendasi_dari,
            'lainnya' => $existingVendor->lainnya
        ];
    }

    // If no existing vendor data, return defaults
    // In production, you might want to store registration data separately
    return [
        'npwp' => null,
        'sumber_informasi' => 'website', // fallback default
        'rekomendasi_dari' => null,
        'lainnya' => null
    ];
}

    private function getFieldDefinitions()
    {
        return [
            1 => [
                'id' => 1,
                'field' => 'nomor_vendor',
                'label' => 'Nomor Vendor',
                'type' => 'text',
                'readonly' => true,
                'required' => true,
                'section' => 'basic'
            ],
            2 => [
                'id' => 2,
                'field' => 'tipe_perusahaan',
                'label' => 'Tipe Perusahaan',
                'type' => 'select',
                'readonly' => true,
                'required' => true,
                'options' => ['SC' => 'Subcontractor', 'DS' => 'Distributor', 'MF' => 'Manufacturer', 'FW' => 'Forwarder'],
                'section' => 'basic'
            ],
            3 => [
                'id' => 3,
                'field' => 'nama_perusahaan',
                'label' => 'Nama Perusahaan',
                'type' => 'text',
                'readonly' => true,
                'required' => true,
                'section' => 'basic'
            ],
            4 => [
                'id' => 4,
                'field' => 'nama_perusahaan_induk',
                'label' => 'Nama Perusahaan Induk',
                'type' => 'text',
                'required' => false,
                'section' => 'company'
            ],
            5 => [
                'id' => 5,
                'field' => 'alamat_perusahaan_induk',
                'label' => 'Alamat Perusahaan Induk',
                'type' => 'textarea',
                'required' => false,
                'section' => 'company'
            ],
            6 => [
                'id' => 6,
                'field' => 'npwp',
                'label' => 'NPWP',
                'type' => 'text',
                'required' => false,
                'pattern' => '\\d{2}\\.\\d{3}\\.\\d{3}\\.\\d{1}-\\d{3}\\.\\d{3}',
                'placeholder' => '00.000.000.0-000.000',
                'section' => 'legal'
            ],
            7 => [
                'id' => 7,
                'field' => 'alamat',
                'label' => 'Alamat',
                'type' => 'textarea',
                'required' => true,
                'section' => 'address'
            ],
            8 => [
                'id' => 8,
                'field' => 'alamat_kantor_pusat',
                'label' => 'Alamat Kantor Pusat',
                'type' => 'textarea',
                'required' => false,
                'section' => 'address'
            ],
            9 => [
                'id' => 9,
                'field' => 'alamat_kantor_operasional',
                'label' => 'Alamat Kantor Operasional',
                'type' => 'textarea',
                'required' => false,
                'section' => 'address'
            ],
            10 => [
                'id' => 10,
                'field' => 'kode_pos',
                'label' => 'Kode Pos',
                'type' => 'text',
                'required' => false,
                'maxlength' => 5,
                'section' => 'address'
            ],
            11 => [
                'id' => 11,
                'field' => 'phone',
                'label' => 'Nomor Telepon',
                'type' => 'tel',
                'required' => true,
                'section' => 'contact'
            ],
            12 => [
                'id' => 12,
                'field' => 'phone_pusat',
                'label' => 'Telepon Pusat',
                'type' => 'tel',
                'required' => false,
                'section' => 'contact'
            ],
            13 => [
                'id' => 13,
                'field' => 'website',
                'label' => 'Website',
                'type' => 'url',
                'required' => false,
                'section' => 'contact'
            ],
            14 => [
                'id' => 14,
                'field' => 'contact_person',
                'label' => 'Contact Person',
                'type' => 'json_array',
                'required' => true,
                'fields' => ['nama', 'jabatan', 'telepon', 'email'],
                'section' => 'personnel'
            ],
            15 => [
                'id' => 15,
                'field' => 'top_level',
                'label' => 'Top Level Management',
                'type' => 'json_array',
                'required' => true,
                'fields' => ['nama', 'jabatan', 'telepon', 'email'],
                'section' => 'personnel'
            ],
            16 => [
                'id' => 16,
                'field' => 'mid_level',
                'label' => 'Mid Level Management',
                'type' => 'json_array',
                'required' => false,
                'fields' => ['nama', 'jabatan', 'telepon', 'email'],
                'section' => 'personnel'
            ],
            17 => [
                'id' => 17,
                'field' => 'sales_marketing',
                'label' => 'Sales & Marketing',
                'type' => 'json_array',
                'required' => false,
                'fields' => ['nama', 'jabatan', 'telepon', 'email'],
                'section' => 'personnel'
            ],
            18 => [
                'id' => 18,
                'field' => 'tanggal_berdiri',
                'label' => 'Tanggal Berdiri',
                'type' => 'date',
                'required' => true,
                'section' => 'company'
            ],
            19 => [
                'id' => 19,
                'field' => 'bagian_grup',
                'label' => 'Bagian Grup',
                'type' => 'select',
                'required' => false,
                'options' => ['ya' => 'Ya', 'tidak' => 'Tidak'],
                'section' => 'company'
            ],
            20 => [
                'id' => 20,
                'field' => 'tanggal_beroperasi',
                'label' => 'Tanggal Mulai Beroperasi',
                'type' => 'date',
                'required' => true,
                'section' => 'company'
            ],
            21 => [
                'id' => 21,
                'field' => 'modal_dasar',
                'label' => 'Modal Dasar',
                'type' => 'currency',
                'required' => false,
                'section' => 'financial'
            ],
            22 => [
                'id' => 22,
                'field' => 'modal_dikeluarkan',
                'label' => 'Modal Dikeluarkan',
                'type' => 'currency',
                'required' => false,
                'section' => 'financial'
            ],
            23 => [
                'id' => 23,
                'field' => 'pemegang_saham',
                'label' => 'Pemegang Saham',
                'type' => 'textarea',
                'required' => false,
                'section' => 'financial'
            ],
            24 => [
                'id' => 24,
                'field' => 'nama_direktur',
                'label' => 'Nama Direktur',
                'type' => 'text',
                'required' => false,
                'section' => 'management'
            ],
            25 => [
                'id' => 25,
                'field' => 'jumlah_karyawan',
                'label' => 'Jumlah Karyawan',
                'type' => 'number',
                'required' => false,
                'section' => 'company'
            ],
            26 => [
                'id' => 26,
                'field' => 'sumber_informasi',
                'label' => 'Sumber Informasi',
                'type' => 'select',
                'readonly' => true,
                'required' => true,
                'options' => [
                    'website' => 'Website',
                    'instagram' => 'Instagram',
                    'facebook' => 'Facebook',
                    'rekomendasi' => 'Rekomendasi',
                    'brosur' => 'Brosur',
                    'lainnya' => 'Lainnya'
                ],
                'section' => 'source'
            ],
            27 => [
                'id' => 27,
                'field' => 'rekomendasi_dari',
                'label' => 'Rekomendasi Dari',
                'type' => 'text',
                'readonly' => true,
                'required' => false,
                'section' => 'source',
                'conditional' => ['sumber_informasi' => 'rekomendasi']
            ],
            28 => [
                'id' => 28,
                'field' => 'lainnya',
                'label' => 'Lainnya',
                'type' => 'text',
                'readonly' => true,
                'required' => false,
                'section' => 'source',
                'conditional' => ['sumber_informasi' => 'lainnya']
            ],
            29 => [
                'id' => 29,
                'field' => 'lainnya',
                'label' => 'Keterangan Tambahan',
                'type' => 'textarea',
                'required' => false,
                'section' => 'additional'
            ],
            30 => [
                'id' => 30,
                'field' => 'kepemilikan',
                'label' => 'Kepemilikan',
                'type' => 'select',
                'required' => false,
                'options' => [
                    'tidak' => 'Tidak Ada',
                    'satu' => 'Satu',
                    'lebih_dari_satu' => 'Lebih dari Satu'
                ],
                'section' => 'assets'
            ],
            31 => [
                'id' => 31,
                'field' => 'kepemilikan_armada',
                'label' => 'Kepemilikan Armada',
                'type' => 'select',
                'required' => false,
                'options' => [
                    'sewa' => 'Sewa',
                    'miliki_pribadi' => 'Miliki Pribadi'
                ],
                'section' => 'assets'
            ]
        ];
    }


    private function getVendorTypeFields($vendorType)
    {
        $mappings = [
            'SC' => [1, 2, 3, 7, 10, 11, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28], // Subcontractor
            'FW' => [1, 2, 3, 6, 18, 20, 14, 15, 16, 17, 8, 10, 12, 11, 13, 30, 31, 26, 27, 28],     // Forwarder
            'DS' => [1, 2, 3, 6, 18, 20, 7, 10, 9, 11, 13, 14, 15, 16, 17, 4, 5, 26, 27, 28],        // Distributor
            'MF' => [1, 2, 3, 6, 10, 18, 20, 7, 11, 13, 15, 16, 17, 4, 26, 27, 28]                   // Manufacturer
        ];

        return $mappings[$vendorType] ?? [];
    }

    private function getVendorTypeName($type)
    {
        $names = [
            'SC' => 'Subcontractor',
            'DS' => 'Distributor',
            'MF' => 'Manufacturer/Fabrication',
            'FW' => 'Forwarder'
        ];

        return $names[$type] ?? 'Unknown';
    }

     private function getVendorTypeConfig($type)
    {
        $configs = [
            'SC' => [
                'name' => 'Subcontractor',
                'icon' => 'fas fa-hard-hat',
                'color' => '#fd7e14',
                'description' => 'Perusahaan yang memberikan pelayanan berupa jasa pekerjaan'
            ],
            'DS' => [
                'name' => 'Distributor',
                'icon' => 'fas fa-truck',
                'color' => '#28a745',
                'description' => 'Perusahaan yang bergerak dalam suplai material/equipment dari pabrikan'
            ],
            'MF' => [
                'name' => 'Manufacturer/Fabrication',
                'icon' => 'fas fa-industry',
                'color' => '#6f42c1',
                'description' => 'Perusahaan yang bergerak dalam bidang fabrikasi atau suplai material/equipment'
            ],
            'FW' => [
                'name' => 'Forwarder',
                'icon' => 'fas fa-shipping-fast',
                'color' => '#20c997',
                'description' => 'Perusahaan yang memberikan pelayanan berupa jasa pengiriman'
            ]
        ];

        return $configs[$type] ?? null;
    }

    
    public function getSubcontractorTabs(Request $request)
{
    try {
        $user = $this->authenticateToken($request);
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $vendor = $user->vendor;
        if (!$vendor) {
            return response()->json(['success' => false, 'message' => 'Vendor profile not found'], 404);
        }

        // Verify this is a subcontractor
        if ($vendor->tipe_perusahaan !== 'SC') {
            return response()->json([
                'success' => false, 
                'message' => 'This vendor is not a subcontractor. Current type: ' . $vendor->tipe_perusahaan
            ], 400);
        }

        // Get or create subcontractor profile
        $subcontractor = $vendor->subcontractor;
        if (!$subcontractor) {
            $subcontractor = Subcontractor::create(['vendor_id' => $vendor->id]);
            $subcontractor->initializeDefaults();
        }

        $tabs = [
            [
                'id' => 'general',
                'label' => 'General Information',
                'icon' => 'fas fa-info-circle',
                'component' => 'SubcontractorGeneral'
            ],
            [
                'id' => 'holding',
                'label' => 'Holding Company',
                'icon' => 'fas fa-building',
                'component' => 'SubcontractorHolding'
            ],
            [
                'id' => 'facilities',
                'label' => 'Facilities Company',
                'icon' => 'fas fa-tools',
                'component' => 'SubcontractorFacilities'
            ],
            [
                'id' => 'employees',
                'label' => 'Total Permanent Construction Employees',
                'icon' => 'fas fa-users',
                'component' => 'SubcontractorEmployees'
            ],
            [
                'id' => 'scope',
                'label' => 'Scope of Work',
                'icon' => 'fas fa-tasks',
                'component' => 'SubcontractorScope'
            ],
            [
                'id' => 'projects',
                'label' => 'Major Projects 3-5',
                'icon' => 'fas fa-project-diagram',
                'component' => 'SubcontractorProjects'
            ],
            [
                'id' => 'knowledge',
                'label' => 'Knowledgeable',
                'icon' => 'fas fa-brain',
                'component' => 'SubcontractorKnowledge'
            ],
            [
                'id' => 'documents',
                'label' => 'Attachment/Documents',
                'icon' => 'fas fa-file-alt',
                'component' => 'SubcontractorDocuments'
            ]
        ];

        return response()->json([
            'success' => true,
            'data' => [
                'vendor_type' => 'SC',
                'vendor_type_name' => 'Subcontractor',
                'tabs' => $tabs,
                'subcontractor_data' => $subcontractor,
                'facilities_options' => Subcontractor::getFacilitiesOptions(),
                'employee_classifications' => Subcontractor::getEmployeeClassifications(),
                'document_checklist' => Subcontractor::getDocumentChecklist()
            ]
        ]);

    } catch (\Exception $e) {
        Log::error('Get subcontractor tabs error: ' . $e->getMessage(), [
            'trace' => $e->getTraceAsString()
        ]);
        
        return response()->json([
            'success' => false,
            'message' => 'Internal server error: ' . $e->getMessage()
        ], 500);
    }
}

public function updateSubcontractor(Request $request)
{
    try {
        $user = $this->authenticateToken($request);
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $vendor = $user->vendor;
        if (!$vendor || $vendor->tipe_perusahaan !== 'SC') {
            return response()->json(['success' => false, 'message' => 'Not a subcontractor vendor'], 404);
        }

        $subcontractor = $vendor->subcontractor;
        if (!$subcontractor) {
            $subcontractor = Subcontractor::create(['vendor_id' => $vendor->id]);
        }

        $updateData = $request->all();
        unset($updateData['vendor_id'], $updateData['id'], $updateData['created_at'], $updateData['updated_at']);

        $subcontractor->update($updateData);

        Log::info('Subcontractor data updated', [
            'vendor_id' => $vendor->id,
            'subcontractor_id' => $subcontractor->id
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Data subcontractor berhasil diperbarui',
            'data' => [
                'subcontractor_data' => $subcontractor->fresh()
            ]
        ]);

    } catch (\Exception $e) {
        Log::error('Update subcontractor data error: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Gagal memperbarui data subcontractor: ' . $e->getMessage()
        ], 500);
    }
}

public function getVendorDocuments(Request $request)
{
    try {
        $user = $this->authenticateToken($request);
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $vendor = $user->vendor;
        if (!$vendor) {
            return response()->json(['success' => false, 'message' => 'Vendor profile not found'], 404);
        }

        // Initialize documents if not exists
        VendorDocument::initializeForVendor($vendor->id, $vendor->tipe_perusahaan);

        // Get all documents for this vendor
        $documents = VendorDocument::where('vendor_id', $vendor->id)
            ->with('reviewer:id,fullname')
            ->orderBy('is_required', 'desc')
            ->orderBy('document_name')
            ->get()
            ->map(function ($doc) {
                return [
                    'id' => $doc->id,
                    'document_type' => $doc->document_type,
                    'document_name' => $doc->document_name,
                    'is_required' => $doc->is_required,
                    'has_expiry_date' => $doc->has_expiry_date,
                    'status' => $doc->status,
                    'status_badge' => $doc->getStatusBadge(),
                    'file_name' => $doc->file_name,
                    'file_size' => $doc->getFormattedFileSize(),
                    'file_url' => $doc->getFileUrl(),
                    'expiry_date' => $doc->expiry_date ? $doc->expiry_date->format('Y-m-d') : null,
                    'is_expired' => $doc->isExpired(),
                    'is_expiring_soon' => $doc->isExpiringSoon(),
                    'admin_notes' => $doc->admin_notes,
                    'rejection_reason' => $doc->rejection_reason,
                    'reviewer_name' => $doc->reviewer ? $doc->reviewer->fullname : null,
                    'reviewed_at' => $doc->reviewed_at ? $doc->reviewed_at->format('Y-m-d H:i:s') : null,
                    'uploaded_at' => $doc->uploaded_at ? $doc->uploaded_at->format('Y-m-d H:i:s') : null,
                    'version' => $doc->version
                ];
            });

        // Calculate statistics
        $stats = [
            'total' => $documents->count(),
            'required' => $documents->where('is_required', true)->count(),
            'uploaded' => $documents->whereIn('status', ['uploaded', 'under_review', 'approved', 'rejected'])->count(),
            'approved' => $documents->where('status', 'approved')->count(),
            'rejected' => $documents->where('status', 'rejected')->count(),
            'pending_review' => $documents->whereIn('status', ['uploaded', 'under_review'])->count(),
            'expired' => $documents->where('is_expired', true)->count(),
            'expiring_soon' => $documents->where('is_expiring_soon', true)->count(),
        ];

        return response()->json([
            'success' => true,
            'data' => [
                'documents' => $documents,
                'stats' => $stats
            ]
        ]);

    } catch (\Exception $e) {
        Log::error('Get vendor documents error: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Failed to load documents: ' . $e->getMessage()
        ], 500);
    }
}

public function uploadDocument(Request $request)
{
    try {
        $user = $this->authenticateToken($request);
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $vendor = $user->vendor;
        if (!$vendor) {
            return response()->json(['success' => false, 'message' => 'Vendor profile not found'], 404);
        }

        // Validasi input
        $validator = Validator::make($request->all(), [
            'document_id' => 'required|exists:vendor_documents,id',
            'file' => 'required|file|max:10240|mimes:pdf,doc,docx,jpg,jpeg,png',
            'expiry_date' => 'nullable|date|after:today'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Ambil dokumen
        $document = VendorDocument::where('id', $request->document_id)
            ->where('vendor_id', $vendor->id)
            ->first();

        if (!$document) {
            return response()->json(['success' => false, 'message' => 'Document not found or access denied'], 404);
        }

        // Upload file baru
        $file = $request->file('file');
        $fileName = time() . '_' . $document->document_type . '.' . $file->getClientOriginalExtension();
        $filePath = $file->storeAs('vendor_documents/' . $vendor->id, $fileName, 'public');

        // Update data dokumen
        $updateData = [
            'file_name' => $file->getClientOriginalName(),
            'file_path' => $filePath,
            'file_size' => $file->getSize(),
            'file_type' => $file->getMimeType(),
            'status' => 'uploaded',
            'uploaded_at' => now(),
            'expiry_date' => $request->expiry_date
        ];

        // Increment versi dokumen
        if ($document->isUploaded()) {
            $updateData['version'] = $document->version + 1;
        }

        $document->update($updateData);

        return response()->json([
            'success' => true,
            'message' => 'Document uploaded successfully',
            'data' => [
                'document' => [
                    'id' => $document->id,
                    'file_url' => $document->getFileUrl(),
                    'expiry_date' => $document->expiry_date,
                    'status' => $document->status
                ]
            ]
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Failed to upload document: ' . $e->getMessage()
        ], 500);
    }
}

public function downloadDocument(Request $request, $documentId)
{
    try {
        $user = $this->authenticateToken($request);
        if (!$user) {
            abort(401, 'Unauthorized');
        }

        $vendor = $user->vendor;
        if (!$vendor) {
            abort(404, 'Vendor profile not found');
        }

        $document = VendorDocument::where('id', $documentId)
            ->where('vendor_id', $vendor->id)
            ->first();

        if (!$document) {
            abort(404, 'Document not found or access denied');
        }

        if (!$document->file_path) {
            abort(404, 'No file associated with this document');
        }

        // ✅ FIX: Get full path to file
        $fullPath = storage_path('app/public/' . $document->file_path);
        
        // Check if file exists
        if (!file_exists($fullPath)) {
            Log::error('File not found in storage', [
                'document_id' => $document->id,
                'file_path' => $document->file_path,
                'full_path' => $fullPath,
                'vendor_id' => $vendor->id
            ]);
            abort(404, 'File not found on server');
        }

        // Log download activity
        Log::info('Document downloaded', [
            'vendor_id' => $vendor->id,
            'document_id' => $document->id,
            'document_type' => $document->document_type,
            'file_name' => $document->file_name
        ]);

        // ✅ FIX: Use Response::download() instead of Storage::download()
        return Response::download(
            $fullPath, 
            $document->file_name ?: 'document.' . pathinfo($document->file_path, PATHINFO_EXTENSION)
        );

    } catch (\Exception $e) {
        Log::error('Download document error: ' . $e->getMessage(), [
            'document_id' => $documentId,
            'trace' => $e->getTraceAsString()
        ]);
        
        abort(500, 'Failed to download document');
    }
}

public function deleteDocument(Request $request, $documentId)
{
    try {
        $user = $this->authenticateToken($request);
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $vendor = $user->vendor;
        if (!$vendor) {
            return response()->json(['success' => false, 'message' => 'Vendor profile not found'], 404);
        }

        $document = VendorDocument::where('id', $documentId)
            ->where('vendor_id', $vendor->id)
            ->first();

        if (!$document) {
            return response()->json([
                'success' => false,
                'message' => 'Document not found'
            ], 404);
        }

        // Only allow deletion if not approved
        if ($document->status === 'approved') {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete approved document'
            ], 400);
        }

        // Delete file from storage
        if ($document->file_path && Storage::disk('public')->exists($document->file_path)) {
            Storage::disk('public')->delete($document->file_path);
        }

        // Reset document to initial state
        $document->update([
            'file_name' => null,
            'file_path' => null,
            'file_size' => null,
            'file_type' => null,
            'status' => 'not_uploaded',
            'uploaded_at' => null,
            'admin_notes' => null,
            'rejection_reason' => null,
            'reviewed_by' => null,
            'reviewed_at' => null,
            'expiry_date' => null,
            'version' => 1
        ]);

        Log::info('Document deleted', [
            'vendor_id' => $vendor->id,
            'document_id' => $document->id,
            'document_type' => $document->document_type
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Document deleted successfully'
        ]);

    } catch (\Exception $e) {
        Log::error('Delete document error: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Failed to delete document'
        ], 500);
    }
}
}