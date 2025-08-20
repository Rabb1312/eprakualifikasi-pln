<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('distributors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vendor_id')->constrained('vendors')->onDelete('cascade');
            
            // TAB 2: ENGINEERING
            $table->text('engineering_activities')->nullable()->comment('Please describe your Engineering activities (if any)');
            
            // TAB 3: AFTER SALES SUPPORT (3 pertanyaan dari form)
            $table->enum('priority_treatment', ['yes', 'no'])->nullable();
            
            $table->enum('complaint_agreement', ['yes', 'no'])->nullable();
            
            $table->enum('has_contact_centre', ['yes', 'no'])->nullable();
            
            $table->timestamps();
            
            // Indexes for better performance
            $table->index('vendor_id');
            $table->index(['vendor_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('distributors');
    }
};