<?php
// database/migrations/2025_08_08_095800_create_vendor_documents_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('vendor_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vendor_id')->constrained()->onDelete('cascade');
            $table->string('document_type'); // e.g., 'validity_sheet', 'iso_9001', etc.
            $table->string('document_number')->nullable();
            $table->string('parent_type')->nullable(); // For sub-documents
            $table->string('group')->nullable();
            $table->string('group_name')->nullable();
            $table->string('document_subtitle')->nullable();
            $table->integer('sub_index')->default(0);// For multiple files under same type
            $table->boolean('allows_multiple')->default(false); // Can have multiple files
            $table->string('document_name'); // Human readable name
            $table->boolean('is_required')->default(false);
            $table->boolean('has_expiry_date')->default(false); // Does this document expire?
            $table->boolean('has_template')->default(false);
            $table->string('template_url')->nullable(); // URL to download template
            $table->string('template_download_url')->nullable(); // URL to download template file
            $table->string('template_filename')->nullable();
            
            // File information
            $table->string('file_name')->nullable(); // Original filename
            $table->string('file_path')->nullable(); // Storage path
            $table->string('file_size')->nullable(); // File size in bytes
            $table->string('file_type')->nullable(); // MIME type
            
            // Expiry information
            $table->date('expiry_date')->nullable(); // When document expires
            $table->boolean('expires_soon')->default(false); // Auto-calculated flag
            
            // Status workflow
            $table->enum('status', [
                'not_uploaded',     // Document belum diupload
                'uploaded',         // Document sudah diupload, menunggu review
                'under_review',     // Sedang direview admin
                'approved',         // Disetujui admin
                'rejected',         // Ditolak admin
                'expired'           // Document sudah expired
            ])->default('not_uploaded');
            
            // Review information
            $table->text('admin_notes')->nullable(); // Catatan admin saat review
            $table->string('rejection_reason')->nullable(); // Alasan penolakan
            $table->foreignId('reviewed_by')->nullable()->constrained('users'); // Admin yang review
            $table->timestamp('reviewed_at')->nullable(); // Kapan direview
            $table->timestamp('uploaded_at')->nullable(); // Kapan diupload
            
            // Version control
            $table->integer('version')->default(1); // Version dokumen (untuk re-upload)
            
            $table->timestamps();
            
            // Indexes
            $table->index(['vendor_id', 'document_type', 'sub_index']);
            $table->index(['status']);
            $table->index(['expiry_date']);
            $table->index(['parent_type']);
            
        });
    }

    public function down()
    {
        Schema::dropIfExists('vendor_documents');
    }
};