<?php
// app/Models/Subcontractor.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subcontractor extends Model
{
    protected $fillable = [
        'vendor_id',
        // Holding Company
        'holding_nama_perusahaan', 'holding_tanggal_berdiri', 'holding_alamat',
        'holding_phone', 'holding_modal_dasar', 'holding_modal_dikeluarkan',
        'holding_pemegang_saham', 'holding_contact_person', 'holding_nama_direktur',
        // Facilities
        'facilities', 'other_services',
        // Employees
        'employees',
        // Scope of Work
        'scope_of_work',
        // Major Projects
        'major_projects',
        // Knowledgeable
        'local_regulation_knowledge', 'regulation_knowledge_details',
        // Documents
        'documents'
    ];

    protected $casts = [
        'holding_modal_dasar' => 'decimal:2',
        'holding_modal_dikeluarkan' => 'decimal:2',
        'holding_contact_person' => 'array',
        'facilities' => 'array',
        'employees' => 'array',
        'major_projects' => 'array',
        'documents' => 'array'
    ];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    // Static methods untuk default data
    public static function getFacilitiesOptions()
    {
        return [
            'design' => 'Design',
            'procurement' => 'Procurement',
            'shop_fabrication' => 'Shop Fabrication',
            'construction' => 'Construction',
            'plant_hire' => 'Plant Hire',
            'scheduling' => 'Scheduling (planning)',
            'stringing' => 'Stringing',
            'erection_substation' => 'Erection for Substation',
            'erection_transmission' => 'Erection for Transmission Line',
            'electrical_erection_power' => 'Electrical Erection for Power Plant',
            'mechanical_erection_power' => 'Mechanical Erection for Power Plant',
            'instrument_erection_power' => 'Instrument Erection for Power Plant',
            'testing_commissioning' => 'Testing & Commissioning'
        ];
    }

    public static function getEmployeeClassifications()
    {
        return [
            'Bricklayers', 'Carpenters', 'Laborers Civil', 'Reinforcing Steel Fixers',
            'Scaffolders', 'Boilermakers', 'Erector Riggers', 'Laborers Mechanical',
            'Millwrights', 'Pipe fitters', 'Welders', 'Sheet Metal workers',
            'Drivers', 'Electricians', 'Instrument Fitters', 'Insulators', 'Others'
        ];
    }

    public static function getDocumentChecklist()
    {
        return [
            ['name' => 'Catalogue/Brochure', 'required' => false],
            ['name' => 'ISO 9001', 'required' => true],
            ['name' => 'ISO 14001', 'required' => false],
            ['name' => 'ISO 45001', 'required' => false],
            ['name' => 'ISO 37001', 'required' => false],
            ['name' => 'Others (SMK3, Kebijakan Anti Korupsi/Penyuapan, OHSAS, etc.)', 'required' => false],
            ['name' => 'Certificate of Association Membership', 'required' => false],
            ['name' => 'Certificate of DPT PLN Group', 'required' => true],
            ['name' => 'Copy of HSE Policy', 'required' => true],
            ['name' => 'Copy of CV Korporasi/Manpower and Certificate of Expertise 1', 'required' => false],
            ['name' => 'Copy of CV Korporasi/Manpower and Certificate of Expertise 2', 'required' => false],
            ['name' => 'Copy of CV Korporasi/Manpower and Certificate of Expertise 3', 'required' => false],
            ['name' => 'List of SOP Document Company', 'required' => false],
            ['name' => 'Current Project List (on going)', 'required' => false],
            ['name' => 'Equipment List', 'required' => true],
            ['name' => 'Manpower List', 'required' => true],
            ['name' => 'Quality Management System/Quality Manual Documents', 'required' => false]
        ];
    }

    public function initializeDefaults()
    {
        // Initialize employees array
        if (empty($this->employees)) {
            $employees = [];
            foreach (self::getEmployeeClassifications() as $classification) {
                $employees[] = [
                    'classification' => $classification,
                    'craft_labor' => 0,
                    'foreman' => 0,
                    'supervisor' => 0,
                    'manager' => 0
                ];
            }
            $this->employees = $employees;
        }

        // Initialize major projects (3 projects)
        if (empty($this->major_projects)) {
            $this->major_projects = [
                [
                    'year_of_execution' => '',
                    'project_name' => '',
                    'project_owner' => '',
                    'main_contractor' => '',
                    'value' => '',
                    'work_package' => '',
                    'scope_of_work' => '',
                    'work_duration' => '',
                    'client_representative' => '',
                    'phone' => ''
                ],
                [
                    'year_of_execution' => '',
                    'project_name' => '',
                    'project_owner' => '',
                    'main_contractor' => '',
                    'value' => '',
                    'work_package' => '',
                    'scope_of_work' => '',
                    'work_duration' => '',
                    'client_representative' => '',
                    'phone' => ''
                ],
                [
                    'year_of_execution' => '',
                    'project_name' => '',
                    'project_owner' => '',
                    'main_contractor' => '',
                    'value' => '',
                    'work_package' => '',
                    'scope_of_work' => '',
                    'work_duration' => '',
                    'client_representative' => '',
                    'phone' => ''
                ]
            ];
        }

        // Initialize documents checklist
        if (empty($this->documents)) {
            $documents = [];
            foreach (self::getDocumentChecklist() as $doc) {
                $documents[] = [
                    'name' => $doc['name'],
                    'required' => $doc['required'],
                    'submitted' => false,
                    'remark' => ''
                ];
            }
            $this->documents = $documents;
        }

        $this->save();
    }
}