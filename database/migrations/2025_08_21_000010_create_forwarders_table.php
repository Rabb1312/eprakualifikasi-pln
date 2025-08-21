<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('forwarders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vendor_id')->constrained()->onDelete('cascade');
            
            // ===== TAB 2: OTHER SERVICES =====
            // Other Services - Jasa Lainnya
            $table->boolean('other_services_none')->default(false);
            $table->boolean('able_to_conduct_expediting')->default(false);
            $table->boolean('able_to_submit_regular_status_report')->default(false);
            $table->boolean('able_to_have_scope_electrical_supervision')->default(false);
            $table->boolean('having_ability_make_temporary_jetty')->default(false);
            $table->boolean('other_services_others')->default(false);
            $table->text('other_services_others_description')->nullable();
            
            // Handling Heavy Lift / Oversized Cargo
            $table->string('heavy_lift_capability')->nullable(); // 'yes' or 'no'
            $table->text('heavy_lift_cooperation_companies')->nullable(); // List of companies
            
            // ===== TAB 3: FACILITIES =====
            // Monitoring Management - Manajemen Pengawasan
            $table->string('monitoring_management')->nullable(); // 'manual', 'computerized', 'both'
            $table->text('monitoring_coordination_description')->nullable();
            
            // Software Applications - Aplikasi Perangkat Lunak
            $table->boolean('software_none')->default(false);
            $table->boolean('software_existing')->default(false);
            $table->boolean('software_shipment_tracking')->default(false);
            $table->text('software_shipment_tracking_description')->nullable();
            $table->boolean('software_others')->default(false);
            $table->text('software_others_description')->nullable();
            
            // Communication Information System
            $table->string('communication_system')->nullable(); // 'yes' or 'no'
            $table->text('communication_system_description')->nullable();
            
            // Project Execution Procedure
            $table->string('project_execution_procedure')->nullable(); // 'yes' or 'no'
            $table->text('project_execution_sop_list')->nullable();
            
            // ===== TAB 4: BRANCH OFFICE =====
            // Indonesian-based Branch Office
            $table->json('indonesian_branch_offices')->nullable();
            // Overseas Branch Office
            $table->json('overseas_branch_offices')->nullable();
            
            // ===== TAB 5: LINE SERVICE =====
            // 1. Shipping Line
            $table->string('shipping_line_relation')->nullable(); // 'none' or 'existing'
            $table->json('shipping_lines')->nullable();
            
            // 2. Airlines Services
            $table->string('airlines_relation')->nullable(); // 'none' or 'existing'
            $table->json('airlines')->nullable();
            
            // ===== TAB 6: INSURANCE (REVISED) =====
            // Insurance - Asuransi
            $table->boolean('insurance_none')->default(false);
            $table->boolean('insurance_all_risk_covered_by_company')->default(false);
            $table->boolean('insurance_liability_with_insurance_company')->default(false);
            $table->boolean('insurance_negotiable_with_without_insurance')->default(false);
            $table->boolean('insurance_others')->default(false);
            $table->text('insurance_others_description')->nullable();
            
            // Insurance claim arrangement description
            $table->text('insurance_claim_arrangement_description')->nullable();
            
            // ===== TAB 7: MANPOWER SUPPORT (REVISED) =====
            // Manpower Support - Tenaga Kerja Perusahaan
            $table->string('manpower_support')->nullable(); // 'none' or 'existing'
            
            // Manpower certifications table structure stored as JSON
            $table->json('manpower_certifications')->nullable();
            /*
            Structure for manpower_certifications:
            [
                {
                    "title": "Staff",
                    "applicable_certification": "ISO Certification",
                    "number_full_time": 10,
                    "number_part_time": 5
                },
                {
                    "title": "Custom Clearance (PPJK)",
                    "applicable_certification": "PPJK License",
                    "number_full_time": 3,
                    "number_part_time": 2
                },
                {
                    "title": "Dangerous Goods",
                    "applicable_certification": "DG Certification",
                    "number_full_time": 2,
                    "number_part_time": 1
                },
                {
                    "title": "Cranide Code",
                    "applicable_certification": "Safety Certification",
                    "number_full_time": 4,
                    "number_part_time": 0
                },
                {
                    "title": "HSE",
                    "applicable_certification": "HSE Certification",
                    "number_full_time": 2,
                    "number_part_time": 1
                },
                {
                    "title": "Advisors",
                    "applicable_certification": "Advisory License",
                    "number_full_time": 1,
                    "number_part_time": 2
                },
                {
                    "title": "Others (please mention)",
                    "applicable_certification": "Custom certification",
                    "number_full_time": 0,
                    "number_part_time": 0
                }
            ]
            */
            
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('forwarders');
    }
};