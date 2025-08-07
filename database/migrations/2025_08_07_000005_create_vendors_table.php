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
         Schema::create('vendors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                  ->unique()
                  ->constrained('users')
                  ->onDelete('cascade')
                  ->comment('FK ke tabel users');
            
            // Auto Generated Fields
            $table->string('nomor_vendor', 20)
                  ->unique()
                  ->comment('Format: SC-0001, DS-0001, FW-0001, MF-0001');
            
            // Data dari Registrasi (auto-filled saat register)
            $table->enum('tipe_perusahaan', ['SC', 'DS', 'FW', 'MF'])
                  ->comment('SC=Subcontractor, DS=Distributor, FW=Forwarder, MF=Manufacturer');
            $table->string('nama_perusahaan', 200)
                  ->comment('Diambil dari registrasi - companyName');
            $table->string('npwp', 25)
                  ->nullable()
                  ->comment('Diambil dari registrasi - format: 00.000.000.0-000.000');
            $table->enum('sumber_informasi', [
                'website', 'instagram', 'facebook', 'rekomendasi', 'brosur', 'lainnya'
            ])->nullable()->comment('Diambil dari registrasi - knownFrom');
            $table->string('rekomendasi_dari', 100)
                  ->nullable()
                  ->comment('Jika sumber_informasi = rekomendasi');
            $table->string('lainnya', 200)
                  ->nullable()
                  ->comment('Jika sumber_informasi = lainnya');
            
            // Data Perusahaan (diisi manual di dashboard vendor)
            $table->string('nama_perusahaan_induk', 200)
                  ->nullable()
                  ->comment('Untuk DS dan MF');
            $table->text('alamat_perusahaan_induk')
                  ->nullable()
                  ->comment('Untuk DS dan MF');
            $table->text('alamat')
                  ->nullable()
                  ->comment('Alamat perusahaan utama - untuk DS dan MF');
            $table->text('alamat_kantor_pusat')
                  ->nullable()
                  ->comment('Untuk FW');
            $table->text('alamat_kantor_operasional')
                  ->nullable()
                  ->comment('Untuk DS');
            $table->string('kode_pos', 10)
                  ->nullable()
                  ->comment('Untuk semua kecuali MF');
            $table->string('phone', 20)
                  ->nullable()
                  ->comment('Telepon utama - untuk semua');
            $table->string('phone_pusat', 20)
                  ->nullable()
                  ->comment('Telepon kantor pusat - untuk FW');
            $table->string('website', 100)
                  ->nullable()
                  ->comment('Website perusahaan - untuk semua kecuali MF');
            
            // Contact Person (JSON format: {"nama":"John Doe","jabatan":"Direktur Utama"})
            $table->json('contact_person')
                  ->nullable()
                  ->comment('Contact person utama - untuk semua kecuali MF');
            $table->json('top_level')
                  ->nullable()
                  ->comment('Manajemen tingkat atas - untuk semua');
            $table->json('mid_level')
                  ->nullable()
                  ->comment('Manajemen tingkat menengah - untuk semua');
            $table->json('sales_marketing')
                  ->nullable()
                  ->comment('Sales & Marketing - untuk semua');
            
            // Data Perusahaan Lengkap
            $table->date('tanggal_berdiri')
                  ->nullable()
                  ->comment('Tanggal berdiri perusahaan - untuk semua');
            $table->enum('bagian_grup', ['ya', 'tidak'])
                  ->nullable()
                  ->comment('Apakah bagian dari grup - untuk SC');
            $table->date('tanggal_beroperasi')
                  ->nullable()
                  ->comment('Tanggal mulai beroperasi - untuk semua');
            $table->decimal('modal_dasar', 15, 2)
                  ->nullable()
                  ->comment('Modal dasar perusahaan - untuk SC');
            $table->decimal('modal_dikeluarkan', 15, 2)
                  ->nullable()
                  ->comment('Modal yang dikeluarkan - untuk SC');
            $table->text('pemegang_saham')
                  ->nullable()
                  ->comment('Data pemegang saham - untuk SC');
            $table->string('nama_direktur', 100)
                  ->nullable()
                  ->comment('Nama direktur utama - untuk SC');
            $table->integer('jumlah_karyawan')
                  ->nullable()
                  ->comment('Jumlah karyawan - untuk SC');
            
            // Khusus Forwarder
            $table->enum('kepemilikan', ['tidak', 'satu', 'lebih dari satu'])
                  ->nullable()
                  ->comment('Kepemilikan armada - khusus FW');
            $table->enum('kepemilikan_armada', ['sewa', 'miliki pribadi'])
                  ->nullable()
                  ->comment('Status kepemilikan armada - khusus FW');
            
            // Status & Progress Tracking
            $table->boolean('profile_completed')
                  ->default(false)
                  ->comment('Status kelengkapan profil');
            $table->decimal('completion_percentage', 5, 2)
                  ->default(0.00)
                  ->comment('Persentase kelengkapan profil (0-100)');
            $table->timestamp('verified_at')
                  ->nullable()
                  ->comment('Tanggal verifikasi oleh admin');
            $table->foreignId('verified_by')
                  ->nullable()
                  ->constrained('users')
                  ->onDelete('set null')
                  ->comment('Admin yang memverifikasi');
            
            // Timestamps
            $table->timestamps();
            
            // Indexes untuk performance
            $table->index('nomor_vendor', 'idx_vendors_nomor');
            $table->index('tipe_perusahaan', 'idx_vendors_tipe');
            $table->index('profile_completed', 'idx_vendors_completed');
            $table->index('verified_at', 'idx_vendors_verified');
            $table->index(['tipe_perusahaan', 'created_at'], 'idx_vendors_tipe_created');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendors');
    }
};