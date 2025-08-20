<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use App\Models\User;
use App\Models\Subcontractor;
use App\Models\Distributor;
use App\Models\VendorDocument;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
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
                'id',
                'user_id',
                'created_at',
                'updated_at',
                'nomor_vendor',
                'tipe_perusahaan',
                'nama_perusahaan',
                'sumber_informasi',
                'rekomendasi_dari',
                'lainnya' // ✅ ADDED: Prevent update
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
                'color' => '#007bff',
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

    // ✅ TAMBAH: Method untuk merge templates dengan uploaded documents
private function mergeTemplatesWithUploaded($templates, $uploadedDocuments)
{
    $uploadedByType = $uploadedDocuments->groupBy('document_type');
    $merged = [];

    foreach ($templates as $template) {
        $documentType = $template['type'];
        
        if (isset($uploadedByType[$documentType])) {
            // ✅ ADA DOKUMEN: Use uploaded document data
            $uploaded = $uploadedByType[$documentType];
            
            if ($template['allows_multiple'] ?? false) {
                // Multiple files - process each one
                foreach ($uploaded as $doc) {
                    $mapped = $this->mapDocumentData($doc);
                    // ✅ ENSURE: Template info is preserved
                    $mapped['has_template'] = $template['has_template'] ?? false;
                    $mapped['template_url'] = $template['template_url'] ?? null;
                    $mapped['template_download_url'] = $template['template_download_url'] ?? null;
                    $mapped['template_filename'] = $template['template_filename'] ?? null;
                    $merged[] = $mapped;
                }
            } else {
                // Single file
                $mapped = $this->mapDocumentData($uploaded->first());
                // ✅ ENSURE: Template info is preserved
                $mapped['has_template'] = $template['has_template'] ?? false;
                $mapped['template_url'] = $template['template_url'] ?? null;
                $mapped['template_download_url'] = $template['template_download_url'] ?? null;
                $mapped['template_filename'] = $template['template_filename'] ?? null;
                $merged[] = $mapped;
            }
        } else {
            // ✅ BELUM ADA: Use template placeholder
            $merged[] = [
                // ✅ NO ID - indicates it's a template
                'document_type' => $template['type'],
                'document_name' => $template['name'],
                'document_subtitle' => $template['subtitle'] ?? '',
                'document_number' => $template['number'],
                'is_required' => $template['required'] ?? false,
                'has_expiry_date' => $template['has_expiry'] ?? false,
                'allows_multiple' => $template['allows_multiple'] ?? false,
                'has_template' => $template['has_template'] ?? false,
                'template_url' => $template['template_url'] ?? null,
                'template_download_url' => $template['template_download_url'] ?? null,
                'template_filename' => $template['template_filename'] ?? null,
                'status' => 'not_uploaded',
                'status_badge' => ['class' => 'secondary', 'icon' => 'fa-upload', 'text' => 'Not Uploaded'],
                'file_name' => null,
                'file_size' => null,
                'file_url' => null,
                'expiry_date' => null,
                'is_expired' => false,
                'is_expiring_soon' => false,
                'admin_notes' => null,
                'rejection_reason' => null,
                'version' => 1,
                'sub_index' => 0,
                'group' => $template['group'] ?? null,
                'group_name' => $template['group_name'] ?? null,
                'parent_type' => $template['parent_type'] ?? null,
                'all_files' => [],
                'file_count' => 0
            ];
        }
    }

    Log::info('Merged documents', [
        'total_merged' => count($merged),
        'with_id' => collect($merged)->filter(function($doc) { return isset($doc['id']); })->count(),
        'templates' => collect($merged)->filter(function($doc) { return !isset($doc['id']); })->count()
    ]);

    return $merged;
}

private function processMergedDocumentsByGroup($docs, &$attachment)
{
    try {
        $documentsByGroup = collect($docs)->groupBy('group');
        $groupedDocuments = [];
        $allDocuments = [];

        foreach ($documentsByGroup as $group => $groupDocs) {
            if (empty($group)) continue;

            $groupDocuments = [];

            // ✅ PROCESS: Each document in group (both template and uploaded)
            foreach ($groupDocs as $doc) {
                if (is_array($doc)) {
                    // ✅ ARRAY: Already processed document
                    $processedDoc = $doc;
                } else {
                    // ✅ MODEL: Uploaded document
                    $processedDoc = $this->mapDocumentData($doc);
                }

                $groupDocuments[] = $processedDoc;
                $allDocuments[] = $processedDoc;
            }

            // Sort by document number within the group
            usort($groupDocuments, function ($a, $b) {
                return version_compare($a['document_number'], $b['document_number']);
            });

            $groupName = $groupDocs->first()['group_name'] ?? 
                         (is_array($groupDocs->first()) ? $groupDocs->first()['group_name'] : $groupDocs->first()->group_name) ?? 
                         "Group $group";
            
            if (!empty($groupDocuments)) {
                $groupedDocuments[$group] = [
                    'group_number' => $group,
                    'group_name' => $groupName,
                    'documents' => $groupDocuments
                ];
            }
        }

        // Sort groups by their number
        ksort($groupedDocuments);

        $attachment['documents'] = $allDocuments;
        $attachment['grouped_documents'] = $groupedDocuments;

        Log::info('Processed merged documents', [
            'groups' => count($groupedDocuments),
            'total_documents' => count($allDocuments)
        ]);
        
    } catch (\Exception $e) {
        Log::error('Error processing merged documents by group: ' . $e->getMessage());
        $attachment['documents'] = [];
        $attachment['grouped_documents'] = [];
    }
}


// ✅ TAMBAH: Process grouped documents untuk template
private function processTemplateGroupedDocuments($docs, &$attachment)
{
    try {
        $documentsByGroup = collect($docs)->groupBy('group');
        $groupedDocuments = [];
        $allDocuments = [];

        foreach ($documentsByGroup as $group => $groupDocs) {
            if (empty($group)) continue;

            $groupDocuments = [];

            foreach ($groupDocs as $doc) {
                $groupDocuments[] = $doc;
                $allDocuments[] = $doc;
            }

            // Sort by document number within the group
            usort($groupDocuments, function ($a, $b) {
                return version_compare($a['document_number'], $b['document_number']);
            });

            $groupName = $groupDocs->first()['group_name'] ?? "Group $group";
            
            if (!empty($groupDocuments)) {
                $groupedDocuments[$group] = [
                    'group_number' => $group,
                    'group_name' => $groupName,
                    'documents' => $groupDocuments
                ];
            }
        }

        // Sort groups by their number
        ksort($groupedDocuments);

        $attachment['documents'] = $allDocuments;
        $attachment['grouped_documents'] = $groupedDocuments;
        
    } catch (\Exception $e) {
        Log::error('Error processing template grouped documents: ' . $e->getMessage());
        $attachment['documents'] = [];
        $attachment['grouped_documents'] = [];
    }
}

// ✅ UBAH: Upload method untuk create document saat upload
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

        // ✅ FLEXIBLE: Handle both document_id and document_type
        $documentId = $request->input('document_id');
        $documentType = $request->input('document_type');

        if (!$documentId && !$documentType) {
            return response()->json([
                'success' => false,
                'message' => 'Either document_id or document_type is required'
            ], 422);
        }

        // ✅ GET OR CREATE: Document
        if ($documentId) {
            // ✅ EXISTING: Get existing document
            $baseDocument = VendorDocument::where('id', $documentId)
                ->where('vendor_id', $vendor->id)
                ->first();

            if (!$baseDocument) {
                return response()->json(['success' => false, 'message' => 'Document not found'], 404);
            }
        } else {
            // ✅ NEW: Create document from template
            $baseDocument = VendorDocument::createDocumentOnUpload(
                $vendor->id, 
                $documentType, 
                $vendor->tipe_perusahaan
            );
        }

        // ✅ DYNAMIC VALIDATION: Based on template
        $allowedMimes = $baseDocument->has_template ? 'pdf,xlsx,xls' : 'pdf';
        
        $validator = Validator::make($request->all(), [
            'files' => 'required|array|min:1',
            'files.*' => "required|file|max:10240|mimes:$allowedMimes",
            'expiry_dates' => 'sometimes|array',
            'expiry_dates.*' => 'nullable|date|after:today',
            'descriptions' => 'sometimes|array',
            'descriptions.*' => 'nullable|string|max:500'
        ]);

        if ($validator->fails()) {
            $errorMessage = $baseDocument->has_template
                ? 'Only PDF and Excel files (XLSX, XLS) are allowed for template documents. Max 10MB per file.'
                : 'Only PDF files are allowed. Max 10MB per file.';

            return response()->json([
                'success' => false,
                'message' => 'Validation failed: ' . $errorMessage,
                'errors' => $validator->errors()
            ], 422);
        }

        $files = $request->file('files');
        $expiryDates = $request->input('expiry_dates', []);
        $descriptions = $request->input('descriptions', []);
        $uploadedDocuments = [];

        // ✅ UPLOAD LOGIC: Same as before
        if ($baseDocument->allows_multiple) {
            foreach ($files as $index => $file) {
                if ($index == 0 && !$baseDocument->isUploaded()) {
                    $document = $baseDocument;
                } else {
                    $document = $this->createMultipleDocumentSlot($baseDocument, $index);
                }

                $this->uploadSingleFile($document, $file, $vendor->id, $expiryDates[$index] ?? null, $descriptions[$index] ?? null);
                $uploadedDocuments[] = $this->formatDocumentResponse($document->fresh());
            }
        } else {
            if (count($files) > 1) {
                return response()->json(['success' => false, 'message' => 'This document type only allows single file upload'], 400);
            }

            $this->uploadSingleFile($baseDocument, $files[0], $vendor->id, $expiryDates[0] ?? null, $descriptions[0] ?? null);
            $uploadedDocuments[] = $this->formatDocumentResponse($baseDocument->fresh());
        }

        return response()->json([
            'success' => true,
            'message' => count($uploadedDocuments) . ' file(s) uploaded successfully',
            'data' => $uploadedDocuments
        ]);

    } catch (\Exception $e) {
        Log::error('Upload document error: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Failed to upload documents: ' . $e->getMessage()
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
            return response()->download(
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
            return response()->json(['success' => false, 'message' => 'Document not found'], 404);
        }

        if ($document->status === 'approved') {
            return response()->json(['success' => false, 'message' => 'Cannot delete approved document'], 400);
        }

        // ✅ NEW LOGIC: Handle multiple files deletion
        if ($document->allows_multiple) {
            // Get all documents with same document_type and vendor_id
            $allDocuments = VendorDocument::where('vendor_id', $document->vendor_id)
                ->where('document_type', $document->document_type)
                ->get();

            $deletedCount = 0;
            $filesDeleted = [];

            foreach ($allDocuments as $doc) {
                // Skip approved documents
                if ($doc->status === 'approved') {
                    continue;
                }

                // Delete file from storage if exists
                if ($doc->file_path && Storage::disk('public')->exists($doc->file_path)) {
                    Storage::disk('public')->delete($doc->file_path);
                    $filesDeleted[] = $doc->file_name;
                }

                // Delete the document record
                $doc->delete();
                $deletedCount++;
            }

            Log::info('Multiple documents deleted', [
                'vendor_id' => $vendor->id,
                'document_type' => $document->document_type,
                'deleted_count' => $deletedCount,
                'files_deleted' => $filesDeleted
            ]);

            $message = $deletedCount > 1 
                ? "Successfully deleted {$deletedCount} files for \"{$document->document_name}\""
                : "Successfully deleted 1 file for \"{$document->document_name}\"";

            return response()->json([
                'success' => true,
                'message' => $message,
                'data' => [
                    'deleted_count' => $deletedCount,
                    'files_deleted' => $filesDeleted
                ]
            ]);

        } else {
            // ✅ EXISTING LOGIC: Single file deletion
            // Delete file from storage
            if ($document->file_path && Storage::disk('public')->exists($document->file_path)) {
                Storage::disk('public')->delete($document->file_path);
            }

            if ($document->sub_index > 0) {
                // Delete the specific file slot
                $document->delete();
                $message = 'File deleted successfully';
            } else {
                // Reset main document to initial state
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
                $message = 'Document reset successfully';
            }

            return response()->json([
                'success' => true,
                'message' => $message
            ]);
        }

    } catch (\Exception $e) {
        Log::error('Delete document error: ' . $e->getMessage(), [
            'document_id' => $documentId,
            'trace' => $e->getTraceAsString()
        ]);
        
        return response()->json([
            'success' => false,
            'message' => 'Failed to delete document: ' . $e->getMessage()
        ], 500);
    }
}

// ✅ TAMBAH: Method untuk get count of deletable files
public function getDeleteableFilesCount(Request $request, $documentId)
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
            return response()->json(['success' => false, 'message' => 'Document not found'], 404);
        }

        if ($document->allows_multiple) {
            $count = VendorDocument::where('vendor_id', $document->vendor_id)
                ->where('document_type', $document->document_type)
                ->where('status', '!=', 'approved')
                ->whereNotNull('file_name')
                ->count();
        } else {
            $count = $document->file_name ? 1 : 0;
        }

        return response()->json([
            'success' => true,
            'data' => [
                'deleteable_count' => $count,
                'document_name' => $document->document_name,
                'allows_multiple' => $document->allows_multiple
            ]
        ]);

    } catch (\Exception $e) {
        Log::error('Get deleteable files count error: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Failed to get deleteable files count'
        ], 500);
    }
}

    private function createMultipleDocumentSlot($baseDocument, $index)
    {
        // Find highest sub_index for this document type
        $maxSubIndex = VendorDocument::where('vendor_id', $baseDocument->vendor_id)
            ->where('document_type', $baseDocument->document_type)
            ->max('sub_index') ?? -1;

        return VendorDocument::create([
            'vendor_id' => $baseDocument->vendor_id,
            'document_type' => $baseDocument->document_type,
            'document_number' => $baseDocument->document_number,
            'document_name' => $baseDocument->document_name . ' #' . ($maxSubIndex + 2),
            'parent_type' => $baseDocument->parent_type,
            'sub_index' => $maxSubIndex + 1,
            'allows_multiple' => $baseDocument->allows_multiple,
            'is_required' => $baseDocument->is_required,
            'has_expiry_date' => $baseDocument->has_expiry_date,
            'status' => 'not_uploaded'
        ]);
    }

    private function uploadSingleFile($document, $file, $vendorId, $expiryDate = null, $description = null)
    {
        $fileName = time() . '_' . $document->sub_index . '_' . $document->document_type . '.' . $file->getClientOriginalExtension();
        $filePath = $file->storeAs('vendor_documents/' . $vendorId, $fileName, 'public');

        $updateData = [
            'file_name' => $file->getClientOriginalName(),
            'file_path' => $filePath,
            'file_size' => $file->getSize(),
            'file_type' => $file->getMimeType(),
            'status' => 'uploaded',
            'uploaded_at' => now(),
            'admin_notes' => $description
        ];

        if ($expiryDate && $document->has_expiry_date) {
            $updateData['expiry_date'] = $expiryDate;
        }

        if ($document->isUploaded()) {
            $updateData['version'] = $document->version + 1;
        }

        $document->update($updateData);
    }

    private function formatDocumentResponse($document)
    {
        return [
            'id' => $document->id,
            'document_type' => $document->document_type,
            'document_name' => $document->document_name,
            'file_name' => $document->file_name,
            'file_size' => $document->getFormattedFileSize(),
            'file_url' => $document->getFileUrl(),
            'status' => $document->status,
            'status_badge' => $document->getStatusBadge(),
            'expiry_date' => $document->expiry_date?->format('Y-m-d'),
            'sub_index' => $document->sub_index,
            'allows_multiple' => $document->allows_multiple,
            'is_expired' => $document->isExpired(),
            'is_expiring_soon' => $document->isExpiringSoon(),
            'version' => $document->version ?? 1
        ];
    }

    /**
     * Map document data helper
     */
    private function mapDocumentData($doc)
{
    $mapped = [
        'id' => $doc->id,
        'document_type' => $doc->document_type,
        'document_name' => $doc->document_name ?? '',
        'document_subtitle' => $doc->document_subtitle ?? '',
        'document_number' => $doc->document_number ?? '',
        'is_required' => $doc->is_required ?? false,
        'has_expiry_date' => $doc->has_expiry_date ?? false,
        'allows_multiple' => $doc->allows_multiple ?? false,
        'has_template' => $doc->has_template ?? false,
        'template_url' => $doc->template_url ?? null,
        'template_download_url' => $doc->template_download_url ?? null,
        'template_filename' => $doc->template_filename ?? null,
        'status' => $doc->status ?? 'not_uploaded',
        'status_badge' => $doc->getStatusBadge(),
        'file_name' => $doc->file_name,
        'file_size' => $doc->getFormattedFileSize(),
        'file_url' => $doc->getFileUrl(),
        'expiry_date' => $doc->expiry_date?->format('Y-m-d'),
        'is_expired' => $doc->isExpired(),
        'is_expiring_soon' => $doc->isExpiringSoon(),
        'admin_notes' => $doc->admin_notes,
        'rejection_reason' => $doc->rejection_reason,
        'version' => $doc->version ?? 1,
        'sub_index' => $doc->sub_index ?? 0,
        'group' => $doc->group ?? null,
        'group_name' => $doc->group_name ?? null,
        'parent_type' => $doc->parent_type ?? null
    ];

    // ✅ HANDLE: Multiple files aggregation
    if ($doc->allows_multiple) {
        // Get all files for this document type
        $allFiles = VendorDocument::where('vendor_id', $doc->vendor_id)
            ->where('document_type', $doc->document_type)
            ->whereNotNull('file_name')
            ->get();

        $mapped['all_files'] = $allFiles->map(function($file) {
            return [
                'id' => $file->id,
                'file_name' => $file->file_name,
                'file_size' => $file->getFormattedFileSize(),
                'file_url' => $file->getFileUrl(),
                'status' => $file->status,
                'status_badge' => $file->getStatusBadge(),
                'version' => $file->version ?? 1,
                'expiry_date' => $file->expiry_date?->format('Y-m-d'),
                'is_expired' => $file->isExpired(),
                'is_expiring_soon' => $file->isExpiringSoon()
            ];
        })->toArray();

        $mapped['file_count'] = $allFiles->count();
        
        // ✅ OVERALL STATUS: untuk multiple files
        if ($allFiles->count() > 0) {
            $mapped['status'] = $this->getOverallStatus($allFiles);
            $mapped['status_badge'] = $this->getOverallStatusBadge($allFiles);
        }
    } else {
        $mapped['all_files'] = [];
        $mapped['file_count'] = $doc->file_name ? 1 : 0;
    }

    return $mapped;
}

    /**
     * Get overall status for multiple files
     */
    private function getOverallStatus($documents)
    {
        $uploaded = $documents->where('status', '!=', 'not_uploaded');

        if ($uploaded->isEmpty()) {
            return 'not_uploaded';
        }

        if ($uploaded->contains('status', 'approved')) {
            return 'approved';
        }

        if ($uploaded->contains('status', 'rejected')) {
            return 'rejected';
        }

        return 'uploaded';
    }

    /**
     * Get overall status badge for multiple files
     */
    private function getOverallStatusBadge($documents)
    {
        $status = $this->getOverallStatus($documents);
        $badges = [
            'not_uploaded' => ['class' => 'secondary', 'icon' => 'fa-upload', 'text' => 'Not Uploaded'],
            'uploaded' => ['class' => 'warning', 'icon' => 'fa-clock', 'text' => 'Uploaded'],
            'approved' => ['class' => 'success', 'icon' => 'fa-check', 'text' => 'Approved'],
            'rejected' => ['class' => 'danger', 'icon' => 'fa-times-circle', 'text' => 'Rejected'],
        ];

        return $badges[$status];
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

        // ✅ ALWAYS USE HYBRID MODE: Template + Uploaded
        return $this->getDocumentsHybridMode($vendor);

    } catch (\Exception $e) {
        Log::error('Get vendor documents error: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Failed to load documents: ' . $e->getMessage()
        ], 500);
    }
}

private function getDocumentsHybridMode($vendor)
{
    // Get templates for this vendor type
    $templates = VendorDocument::getDocumentTemplatesForVendor($vendor->tipe_perusahaan);
    
    // Get uploaded documents
    $uploadedDocuments = VendorDocument::where('vendor_id', $vendor->id)
        ->with('reviewer:id,fullname')
        ->get();

    Log::info('Hybrid mode data', [
        'vendor_id' => $vendor->id,
        'vendor_type' => $vendor->tipe_perusahaan,
        'templates_count' => count($templates),
        'uploaded_count' => $uploadedDocuments->count()
    ]);

    // ✅ MERGE: Templates with uploaded documents
    $mergedDocuments = $this->mergeTemplatesWithUploaded($templates, $uploadedDocuments);

    // Group by attachment type
    $groupedByAttachment = collect($mergedDocuments)->groupBy('parent_type');

    $attachments = [
        'attachment_1' => [
            'title' => 'ATTACHMENT 1',
            'tab_id' => 'attachment_1',
            'documents' => [],
            'grouped_documents' => []
        ],
        'attachment_2' => [
            'title' => 'ATTACHMENT 2',
            'tab_id' => 'attachment_2',
            'documents' => [],
            'grouped_documents' => []
        ]
    ];

    foreach ($groupedByAttachment as $attachmentType => $docs) {
        if (!isset($attachments[$attachmentType])) continue;

        // Process merged documents
        $this->processMergedDocumentsByGroup($docs, $attachments[$attachmentType]);
    }

    // Calculate stats
    $uploadedDocsCollection = collect($mergedDocuments)->filter(function($doc) {
        return isset($doc['id']); // Only count uploaded documents
    });

    $stats = [
        'total' => count($templates),
        'required' => collect($templates)->where('required', true)->count(),
        'uploaded' => $uploadedDocsCollection->where('status', '!=', 'not_uploaded')->count(),
        'approved' => $uploadedDocsCollection->where('status', 'approved')->count(),
        'rejected' => $uploadedDocsCollection->where('status', 'rejected')->count(),
        'pending_review' => $uploadedDocsCollection->whereIn('status', ['uploaded', 'under_review'])->count(),
    ];

    return response()->json([
        'success' => true,
        'data' => [
            'attachments' => $attachments,
            'stats' => $stats,
            'mode' => 'hybrid' // ✅ DEBUG
        ]
    ]);
}


private function getDocumentsTemplateMode($vendor)
{
    // Get templates for this vendor type
    $templates = VendorDocument::getDocumentTemplatesForVendor($vendor->tipe_perusahaan);
    
    // Group templates by attachment type
    $templatesByAttachment = collect($templates)->groupBy('parent_type');

    $attachments = [
        'attachment_1' => [
            'title' => 'ATTACHMENT 1',
            'tab_id' => 'attachment_1',
            'documents' => [],
            'grouped_documents' => []
        ],
        'attachment_2' => [
            'title' => 'ATTACHMENT 2',
            'tab_id' => 'attachment_2',
            'documents' => [],
            'grouped_documents' => []
        ]
    ];

    foreach ($templatesByAttachment as $attachmentType => $templates) {
        if (!isset($attachments[$attachmentType])) continue;

        // Process templates into display format
        $this->processTemplatesByGroup($templates, $attachments[$attachmentType]);
    }

    // Calculate stats from templates
    $totalTemplates = count($templates);
    $requiredTemplates = collect($templates)->where('required', true)->count();

    $stats = [
        'total' => $totalTemplates,
        'required' => $requiredTemplates,
        'uploaded' => 0,
        'approved' => 0,
        'rejected' => 0,
        'pending_review' => 0,
    ];

    return response()->json([
        'success' => true,
        'data' => [
            'attachments' => $attachments,
            'stats' => $stats,
            'mode' => 'template' // ✅ DEBUG
        ]
    ]);
}

// ✅ TAMBAH: Process templates by group
private function processTemplatesByGroup($templates, &$attachment)
{
    try {
        $templatesByGroup = collect($templates)->groupBy('group');
        $groupedDocuments = [];
        $allDocuments = [];

        foreach ($templatesByGroup as $group => $groupTemplates) {
            if (empty($group)) continue;

            $groupDocuments = [];

            foreach ($groupTemplates as $template) {
                $templateDoc = [
                    // ✅ NO ID for templates - this signals it's not uploaded yet
                    'document_type' => $template['type'],
                    'document_name' => $template['name'],
                    'document_subtitle' => $template['subtitle'] ?? '',
                    'document_number' => $template['number'],
                    'is_required' => $template['required'] ?? false,
                    'has_expiry_date' => $template['has_expiry'] ?? false,
                    'allows_multiple' => $template['allows_multiple'] ?? false,
                    'has_template' => $template['has_template'] ?? false,
                    'template_url' => $template['template_url'] ?? null,
                    'template_download_url' => $template['template_download_url'] ?? null,
                    'template_filename' => $template['template_filename'] ?? null,
                    'status' => 'not_uploaded',
                    'status_badge' => ['class' => 'secondary', 'icon' => 'fa-upload', 'text' => 'Not Uploaded'],
                    'file_name' => null,
                    'file_size' => null,
                    'file_url' => null,
                    'expiry_date' => null,
                    'is_expired' => false,
                    'is_expiring_soon' => false,
                    'admin_notes' => null,
                    'rejection_reason' => null,
                    'version' => 1,
                    'sub_index' => 0,
                    'group' => $template['group'] ?? null,
                    'group_name' => $template['group_name'] ?? null,
                    'parent_type' => $template['parent_type'] ?? null,
                    'all_files' => [],
                    'file_count' => 0
                ];

                $groupDocuments[] = $templateDoc;
                $allDocuments[] = $templateDoc;
            }

            // Sort by document number
            usort($groupDocuments, function ($a, $b) {
                return version_compare($a['document_number'], $b['document_number']);
            });

            $groupName = $groupTemplates->first()['group_name'] ?? "Group $group";
            
            if (!empty($groupDocuments)) {
                $groupedDocuments[$group] = [
                    'group_number' => $group,
                    'group_name' => $groupName,
                    'documents' => $groupDocuments
                ];
            }
        }

        // Sort groups by their number
        ksort($groupedDocuments);

        $attachment['documents'] = $allDocuments;
        $attachment['grouped_documents'] = $groupedDocuments;
        
    } catch (\Exception $e) {
        Log::error('Error processing templates by group: ' . $e->getMessage());
        $attachment['documents'] = [];
        $attachment['grouped_documents'] = [];
    }
}



    /**
     * Process documents with group structure - FIXED VERSION
     */
    private function processGroupedDocuments($docs, &$attachment)
{
    try {
        // ✅ DEBUG: Log document structure
        Log::info('Processing grouped documents', [
            'total_docs' => $docs->count(),
            'sample_doc' => $docs->first() ? [
                'id' => $docs->first()->id,
                'type' => $docs->first()->document_type,
                'group' => $docs->first()->group,
                'group_name' => $docs->first()->group_name,
                'number' => $docs->first()->document_number
            ] : null
        ]);

        // ✅ CHECK: Apakah ada kolom group
        $hasGroupColumn = $docs->first() && !is_null($docs->first()->group);
        
        if (!$hasGroupColumn) {
            Log::warning('No group column found, falling back to simple structure');
            return $this->processSimpleDocuments($docs, $attachment);
        }

        $documentsByGroup = $docs->groupBy('group');
        $groupedDocuments = [];
        $allDocuments = [];
        
        Log::info('Documents by group', [
            'groups' => $documentsByGroup->keys()->toArray(),
            'group_counts' => $documentsByGroup->map->count()->toArray()
        ]);

        foreach ($documentsByGroup as $group => $groupDocs) {
            if (empty($group)) {
                Log::warning('Empty group found, skipping', ['doc_ids' => $groupDocs->pluck('id')->toArray()]);
                continue;
            }

            $documentsByType = $groupDocs->groupBy('document_type');
            $groupDocuments = [];

            foreach ($documentsByType as $docType => $docGroup) {
                $baseDoc = $docGroup->first();

                if ($baseDoc->allows_multiple) {
                    // Handle multiple files logic...
                    $documentsWithFiles = $docGroup->filter(function ($doc) {
                        return $doc->file_name || $doc->sub_index === 0;
                    });

                    $allFiles = $documentsWithFiles
                        ->filter(function ($doc) {
                            return $doc->file_name;
                        })
                        ->map(function ($doc) {
                            return $this->mapDocumentData($doc);
                        })
                        ->values()
                        ->toArray();

                    $processedDoc = [
                        'id' => $baseDoc->id,
                        'document_type' => $baseDoc->document_type,
                        'document_name' => $baseDoc->document_name,
                        'document_subtitle' => $baseDoc->document_subtitle ?? '',
                        'document_number' => $baseDoc->document_number,
                        'is_required' => $baseDoc->is_required,
                        'has_expiry_date' => $baseDoc->has_expiry_date,
                        'allows_multiple' => true,
                        'status' => count($allFiles) > 0 ? $this->getOverallStatus($documentsWithFiles) : 'not_uploaded',
                        'status_badge' => count($allFiles) > 0 ? $this->getOverallStatusBadge($documentsWithFiles) : $baseDoc->getStatusBadge(),
                        'all_files' => $allFiles,
                        'file_count' => count($allFiles),
                        'group' => $baseDoc->group,
                        'group_name' => $baseDoc->group_name,
                        // Add other required fields...
                        'has_template' => $baseDoc->has_template ?? false,
                        'template_download_url' => $baseDoc->template_download_url,
                        'template_filename' => $baseDoc->template_filename,
                        'file_name' => $baseDoc->file_name,
                        'file_size' => $baseDoc->getFormattedFileSize(),
                        'file_url' => $baseDoc->getFileUrl(),
                        'expiry_date' => $baseDoc->expiry_date?->format('Y-m-d'),
                        'is_expired' => $baseDoc->isExpired(),
                        'is_expiring_soon' => $baseDoc->isExpiringSoon(),
                        'admin_notes' => $baseDoc->admin_notes,
                        'rejection_reason' => $baseDoc->rejection_reason,
                        'version' => $baseDoc->version ?? 1,
                        'sub_index' => $baseDoc->sub_index ?? 0
                    ];
                } else {
                    // Single file document
                    $processedDoc = $this->mapDocumentData($baseDoc);
                }

                $groupDocuments[] = $processedDoc;
                $allDocuments[] = $processedDoc;
            }

            // ✅ SORT: Sort by document number within the group
            usort($groupDocuments, function ($a, $b) {
                return version_compare($a['document_number'], $b['document_number']);
            });

            // ✅ ADD: Ensure group name is populated
            $groupName = $groupDocs->first()->group_name ?? "Group $group";
            
            if (!empty($groupDocuments)) {
                $groupedDocuments[$group] = [
                    'group_number' => $group,
                    'group_name' => $groupName,
                    'documents' => $groupDocuments
                ];
            }
        }

        // ✅ SORT: Sort groups by their number
        ksort($groupedDocuments);

        Log::info('Grouped documents processed', [
            'total_groups' => count($groupedDocuments),
            'group_numbers' => array_keys($groupedDocuments),
            'total_documents' => count($allDocuments)
        ]);

        // Store both formats
        $attachment['documents'] = $allDocuments;
        $attachment['grouped_documents'] = $groupedDocuments;
        
    } catch (\Exception $e) {
        Log::error('Error processing grouped documents: ' . $e->getMessage());
        // Fallback to simple processing
        $this->processSimpleDocuments($docs, $attachment);
    }
}

    /**
     * Process documents without group structure (fallback)
     */
    private function processSimpleDocuments($docs, &$attachment)
    {
        try {
            // Group by document type for multiple files support
            $documentsByType = $docs->groupBy('document_type');
            $allDocuments = [];

            foreach ($documentsByType as $docType => $docGroup) {
                $baseDoc = $docGroup->first();

                if ($baseDoc->allows_multiple) {
                    // For multiple upload documents
                    $allFiles = $docGroup->map(function ($doc) {
                        return $this->mapDocumentData($doc);
                    })->toArray();

                    $allDocuments[] = [
                        'id' => $baseDoc->id,
                        'document_type' => $baseDoc->document_type,
                        'document_name' => $baseDoc->document_name,
                        'document_subtitle' => $baseDoc->document_subtitle ?? '',
                        'document_number' => $baseDoc->document_number,
                        'is_required' => $baseDoc->is_required,
                        'has_expiry_date' => $baseDoc->has_expiry_date,
                        'allows_multiple' => true,
                        'status' => $this->getOverallStatus($docGroup),
                        'status_badge' => $this->getOverallStatusBadge($docGroup),
                        'all_files' => $allFiles,
                        'file_count' => $docGroup->where('status', '!=', 'not_uploaded')->count()
                    ];
                } else {
                    // Single file document
                    $allDocuments[] = $this->mapDocumentData($baseDoc);
                }
            }

            // Sort by document number
            usort($allDocuments, function ($a, $b) {
                return version_compare($a['document_number'], $b['document_number']);
            });

            $attachment['documents'] = $allDocuments;
            $attachment['grouped_documents'] = []; // Empty for simple structure

        } catch (\Exception $e) {
            Log::error('Error processing simple documents: ' . $e->getMessage());
            $attachment['documents'] = [];
            $attachment['grouped_documents'] = [];
        }
    }

    /**
     * Get distributor tabs configuration and data
     */
    public function getDistributorTabs(Request $request)
    {
        try {
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

            // Verify this is a distributor
            if ($vendor->tipe_perusahaan !== 'DS') {
                return response()->json([
                    'success' => false,
                    'message' => 'This vendor is not a distributor. Current type: ' . $vendor->tipe_perusahaan
                ], 400);
            }

            // Get or create distributor profile
            $distributor = $vendor->distributor;
            if (!$distributor) {
                $distributor = Distributor::create(['vendor_id' => $vendor->id]);
                $distributor->initializeDefaults();
            }

            $tabs = [
                [
                    'id' => 'general',
                    'label' => 'General Information',
                    'icon' => 'fas fa-info-circle',
                    'component' => 'DistributorGeneral',
                    'completed' => false // Will be calculated in frontend
                ],
                [
                    'id' => 'engineering',
                    'label' => 'Engineering',
                    'icon' => 'fas fa-cogs',
                    'component' => 'DistributorEngineering',
                    'completed' => !empty($distributor->engineering_activities)
                ],
                [
                    'id' => 'after_sales',
                    'label' => 'After Sales',
                    'icon' => 'fas fa-headset',
                    'component' => 'DistributorAfterSales',
                    'completed' => !empty($distributor->priority_treatment) &&
                        !empty($distributor->complaint_agreement) &&
                        !empty($distributor->has_contact_centre)
                ],
                [
                    'id' => 'documents',
                    'label' => 'Attachments/Documents',
                    'icon' => 'fas fa-file-alt',
                    'component' => 'DistributorDocuments',
                    'completed' => false // Will be calculated based on document status
                ]
            ];

            return response()->json([
                'success' => true,
                'data' => [
                    'tabs' => $tabs,
                    'vendor' => $vendor->load(['user']),
                    'distributor' => $distributor,
                    'options' => [
                        'yes_no' => Distributor::getYesNoOptions()
                    ],
                    'completion_percentage' => $vendor->getCompletionPercentage()
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting distributor tabs', [
                'user_id' => $request->user()->id ?? null,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update distributor data
     */
    public function updateDistributorData(Request $request)
    {
        try {
            $user = $this->authenticateToken($request);
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 401);
            }

            $vendor = $user->vendor;
            if (!$vendor || $vendor->tipe_perusahaan !== 'DS') {
                return response()->json([
                    'success' => false,
                    'message' => 'Not a distributor vendor'
                ], 404);
            }

            // Validation rules
            $rules = [
                'engineering_activities' => 'nullable|string|max:5000',
                'priority_treatment' => 'nullable|in:yes,no',
                'complaint_agreement' => 'nullable|in:yes,no',
                'has_contact_centre' => 'nullable|in:yes,no',
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Get or create distributor profile
            $distributor = $vendor->distributor;
            if (!$distributor) {
                $distributor = Distributor::create(['vendor_id' => $vendor->id]);
            }

            // Update data
            $updateData = $request->only([
                'engineering_activities',
                'priority_treatment',
                'complaint_agreement',
                'has_contact_centre'
            ]);

            $distributor->update($updateData);

            // Recalculate completion percentage
            $vendor->completion_percentage = $vendor->getCompletionPercentage();
            $vendor->save();

            Log::info('Distributor data updated successfully', [
                'vendor_id' => $vendor->id,
                'distributor_id' => $distributor->id,
                'updated_by' => $user->id,
                'updated_fields' => array_keys($updateData)
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Data distributor berhasil diperbarui',
                'data' => [
                    'vendor' => $vendor->fresh()->load(['user']),
                    'distributor' => $distributor->fresh(),
                    'completion_percentage' => $vendor->completion_percentage
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error updating distributor data', [
                'user_id' => $request->user()->id ?? null,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui data distributor: ' . $e->getMessage()
            ], 500);
        }
    }

    // Update existing getVendorProfile method
    public function getVendorProfile(Request $request)
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

            $data = [
                'vendor' => $vendor->load(['user']),
                'completion_percentage' => $vendor->getCompletionPercentage(),
            ];

            // Add specific data based on vendor type
            switch ($vendor->tipe_perusahaan) {
                case 'SC':
                    $data['subcontractor'] = $vendor->subcontractor;
                    $data['component'] = 'SubcontractorProfile';
                    break;

                case 'DS':
                    // Get or create distributor profile
                    $distributor = $vendor->distributor;
                    if (!$distributor) {
                        $distributor = Distributor::create(['vendor_id' => $vendor->id]);
                        $distributor->initializeDefaults();
                    }
                    $data['distributor'] = $distributor;
                    $data['component'] = 'DistributorProfile';
                    break;

                case 'FW':
                    $data['component'] = 'ForwarderProfile';
                    break;

                case 'MF':
                    $data['component'] = 'ManufacturerProfile';
                    break;

                default:
                    $data['component'] = 'VendorProfile';
            }

            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting vendor profile', [
                'user_id' => $request->user()->id ?? null,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()
            ], 500);
        }
    }

}