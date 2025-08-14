<?php
// database/migrations/create_subcontractors_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('subcontractors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vendor_id')->constrained()->onDelete('cascade');
            
            // ✅ TAB 1: General Information (9 fields dari vendors table - sudah ada)
            // Tidak perlu duplikasi, ambil dari vendors table
            
            // ✅ TAB 2: Holding Company
            $table->string('holding_nama_perusahaan')->nullable();
            $table->date('holding_tanggal_berdiri')->nullable();
            $table->text('holding_alamat')->nullable();
            $table->string('holding_phone')->nullable();
            $table->decimal('holding_modal_dasar', 15, 2)->nullable();
            $table->decimal('holding_modal_dikeluarkan', 15, 2)->nullable();
            $table->text('holding_pemegang_saham')->nullable();
            $table->json('holding_contact_person')->nullable();
            $table->string('holding_nama_direktur')->nullable();
            
            // ✅ TAB 3: Facilities Company (JSON array untuk checkboxes)
            $table->json('facilities')->nullable(); // Array of selected facilities
            $table->text('other_services')->nullable();
            
            // ✅ TAB 4: Total Permanent Construction Employees (JSON array untuk table data)
            $table->json('employees')->nullable(); // Array of employee data by classification
            
            // ✅ TAB 5: Scope of Work
            $table->text('scope_of_work')->nullable();
            
            // ✅ TAB 6: Major Projects 3-5 (JSON array untuk multiple projects)
            $table->json('major_projects')->nullable(); // Array of project objects
            
            // ✅ TAB 7: Knowledgeable
            $table->enum('local_regulation_knowledge', ['yes', 'no'])->nullable();
            $table->text('regulation_knowledge_details')->nullable();
            
            // ✅ TAB 8: Documents/Attachments (JSON array untuk document checklist)
            $table->json('documents')->nullable(); // Array of document submission status
            
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('subcontractors');
    }
};