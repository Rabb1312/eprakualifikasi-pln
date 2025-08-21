<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Forwarder extends Model
{
    use HasFactory;

    protected $fillable = [
        'vendor_id',
        
        // Tab 2: Other Services
        'other_services_none',
        'able_to_conduct_expediting',
        'able_to_submit_regular_status_report',
        'able_to_have_scope_electrical_supervision',
        'having_ability_make_temporary_jetty',
        'other_services_others',
        'other_services_others_description',
        'heavy_lift_capability',
        'heavy_lift_cooperation_companies',
        
        // Tab 3: Facilities
        'monitoring_management',
        'monitoring_coordination_description',
        'software_none',
        'software_existing',
        'software_shipment_tracking',
        'software_shipment_tracking_description',
        'software_others',
        'software_others_description',
        'communication_system',
        'communication_system_description',
        'project_execution_procedure',
        'project_execution_sop_list',
        
        // Tab 4: Branch Office
        'indonesian_branch_offices',
        'overseas_branch_offices',
        
        // Tab 5: Line Service
        'shipping_line_relation',
        'shipping_lines',
        'airlines_relation',
        'airlines',
        
        // Tab 6: Insurance (Revised)
        'insurance_none',
        'insurance_all_risk_covered_by_company',
        'insurance_liability_with_insurance_company',
        'insurance_negotiable_with_without_insurance',
        'insurance_others',
        'insurance_others_description',
        'insurance_claim_arrangement_description',
        
        // Tab 7: Manpower Support (Revised)
        'manpower_support',
        'manpower_certifications'
    ];

    protected $casts = [
        // Boolean fields for Other Services
        'other_services_none' => 'boolean',
        'able_to_conduct_expediting' => 'boolean',
        'able_to_submit_regular_status_report' => 'boolean',
        'able_to_have_scope_electrical_supervision' => 'boolean',
        'having_ability_make_temporary_jetty' => 'boolean',
        'other_services_others' => 'boolean',
        
        // Boolean fields for Facilities
        'software_none' => 'boolean',
        'software_existing' => 'boolean',
        'software_shipment_tracking' => 'boolean',
        'software_others' => 'boolean',
        
        // Boolean fields for Insurance
        'insurance_none' => 'boolean',
        'insurance_all_risk_covered_by_company' => 'boolean',
        'insurance_liability_with_insurance_company' => 'boolean',
        'insurance_negotiable_with_without_insurance' => 'boolean',
        'insurance_others' => 'boolean',
        
        // JSON fields
        'indonesian_branch_offices' => 'array',
        'overseas_branch_offices' => 'array',
        'shipping_lines' => 'array',
        'airlines' => 'array',
        'manpower_certifications' => 'array'
    ];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function initializeDefaults()
    {
        $this->update([
            'indonesian_branch_offices' => [],
            'overseas_branch_offices' => [],
            'shipping_lines' => [],
            'airlines' => [],
            'manpower_certifications' => $this->getDefaultManpowerCertifications()
        ]);
    }

    // Default manpower certifications structure
    public function getDefaultManpowerCertifications()
    {
        return [
            [
                'title' => 'Staff',
                'applicable_certification' => '',
                'number_full_time' => 0,
                'number_part_time' => 0
            ],
            [
                'title' => 'Custom Clearance (PPJK)',
                'applicable_certification' => '',
                'number_full_time' => 0,
                'number_part_time' => 0
            ],
            [
                'title' => 'Dangerous Goods',
                'applicable_certification' => '',
                'number_full_time' => 0,
                'number_part_time' => 0
            ],
            [
                'title' => 'Cranide Code',
                'applicable_certification' => '',
                'number_full_time' => 0,
                'number_part_time' => 0
            ],
            [
                'title' => 'HSE',
                'applicable_certification' => '',
                'number_full_time' => 0,
                'number_part_time' => 0
            ],
            [
                'title' => 'Advisors',
                'applicable_certification' => '',
                'number_full_time' => 0,
                'number_part_time' => 0
            ],
            [
                'title' => 'Others (please mention)',
                'applicable_certification' => '',
                'number_full_time' => 0,
                'number_part_time' => 0
            ]
        ];
    }

    // Static options untuk dropdown
    public static function getMonitoringManagementOptions()
    {
        return [
            'manual' => 'Manual',
            'computerized' => 'Computerized',
            'both' => 'Both'
        ];
    }

    public static function getYesNoOptions()
    {
        return [
            'yes' => 'Yes',
            'no' => 'No'
        ];
    }

    public static function getShippingLineRelationOptions()
    {
        return [
            'none' => 'None',
            'existing' => 'Existing'
        ];
    }

    public static function getAirlinesRelationOptions()
    {
        return [
            'none' => 'None',
            'existing' => 'Existing'
        ];
    }

    public static function getManpowerSupportOptions()
    {
        return [
            'none' => 'None',
            'existing' => 'Existing'
        ];
    }

    // Calculate total manpower
    public function getTotalManpower()
    {
        $total = [
            'full_time' => 0,
            'part_time' => 0,
            'grand_total' => 0
        ];

        if ($this->manpower_certifications) {
            foreach ($this->manpower_certifications as $cert) {
                $total['full_time'] += (int)($cert['number_full_time'] ?? 0);
                $total['part_time'] += (int)($cert['number_part_time'] ?? 0);
            }
        }

        $total['grand_total'] = $total['full_time'] + $total['part_time'];

        return $total;
    }
}