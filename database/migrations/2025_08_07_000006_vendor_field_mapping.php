<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('vendor_field_mappings', function (Blueprint $table) {
            $table->id();
            $table->enum('tipe_perusahaan', ['SC', 'DS', 'FW', 'MF']);
            $table->string('field_name', 50);
            $table->string('field_label', 100);
            $table->boolean('is_required')->default(true);
            $table->integer('field_order')->default(0);
            $table->string('field_type', 20)->default('text'); // text, select, date, number, json
            $table->json('field_options')->nullable(); // untuk dropdown options
            $table->timestamps();
            
            $table->unique(['tipe_perusahaan', 'field_name'], 'unique_tipe_field');
            $table->index('tipe_perusahaan', 'idx_field_tipe');
        });

        // Insert field mappings
        $this->insertFieldMappings();
    }

    /**
     * Insert field mappings untuk setiap tipe vendor
     */
    private function insertFieldMappings(): void
    {
        $mappings = [
            // Subcontractor (SC)
            ['SC', 'nama_perusahaan', 'Nama Perusahaan', true, 1, 'text'],
            ['SC', 'kode_pos', 'Kode Pos', true, 2, 'text'],
            ['SC', 'phone', 'Telepon', true, 3, 'text'],
            ['SC', 'website', 'Website', true, 4, 'url'],
            ['SC', 'contact_person', 'Contact Person', true, 5, 'json'],
            ['SC', 'top_level', 'Top Level Management', true, 6, 'json'],
            ['SC', 'mid_level', 'Mid Level Management', true, 7, 'json'],
            ['SC', 'sales_marketing', 'Sales & Marketing', true, 8, 'json'],
            ['SC', 'tanggal_berdiri', 'Tanggal Berdiri', true, 9, 'date'],
            ['SC', 'bagian_grup', 'Bagian Grup', true, 10, 'select'],
            ['SC', 'tanggal_beroperasi', 'Tanggal Beroperasi', true, 11, 'date'],
            ['SC', 'modal_dasar', 'Modal Dasar', true, 12, 'number'],
            ['SC', 'modal_dikeluarkan', 'Modal Dikeluarkan', true, 13, 'number'],
            ['SC', 'pemegang_saham', 'Pemegang Saham', true, 14, 'textarea'],
            ['SC', 'nama_direktur', 'Nama Direktur', true, 15, 'text'],
            ['SC', 'jumlah_karyawan', 'Jumlah Karyawan', true, 16, 'number'],

            // Forwarder (FW)
            ['FW', 'nama_perusahaan', 'Nama Perusahaan', true, 1, 'text'],
            ['FW', 'npwp', 'NPWP', true, 2, 'text'],
            ['FW', 'tanggal_berdiri', 'Tanggal Berdiri', true, 3, 'date'],
            ['FW', 'tanggal_beroperasi', 'Tanggal Beroperasi', true, 4, 'date'],
            ['FW', 'contact_person', 'Contact Person', true, 5, 'json'],
            ['FW', 'top_level', 'Top Level Management', true, 6, 'json'],
            ['FW', 'mid_level', 'Mid Level Management', true, 7, 'json'],
            ['FW', 'sales_marketing', 'Sales & Marketing', true, 8, 'json'],
            ['FW', 'alamat_kantor_pusat', 'Alamat Kantor Pusat', true, 9, 'textarea'],
            ['FW', 'kode_pos', 'Kode Pos', true, 10, 'text'],
            ['FW', 'phone_pusat', 'Telepon Pusat', true, 11, 'text'],
            ['FW', 'phone', 'Telepon', true, 12, 'text'],
            ['FW', 'website', 'Website', true, 13, 'url'],
            ['FW', 'kepemilikan', 'Kepemilikan', true, 14, 'select'],
            ['FW', 'kepemilikan_armada', 'Kepemilikan Armada', true, 15, 'select'],

            // Distributor (DS)
            ['DS', 'nama_perusahaan', 'Nama Perusahaan', true, 1, 'text'],
            ['DS', 'tanggal_berdiri', 'Tanggal Berdiri', true, 2, 'date'],
            ['DS', 'tanggal_beroperasi', 'Tanggal Beroperasi', true, 3, 'date'],
            ['DS', 'alamat', 'Alamat', true, 4, 'textarea'],
            ['DS', 'kode_pos', 'Kode Pos', true, 5, 'text'],
            ['DS', 'alamat_kantor_operasional', 'Alamat Kantor Operasional', true, 6, 'textarea'],
            ['DS', 'phone', 'Telepon', true, 7, 'text'],
            ['DS', 'website', 'Website', true, 8, 'url'],
            ['DS', 'contact_person', 'Contact Person', true, 9, 'json'],
            ['DS', 'top_level', 'Top Level Management', true, 10, 'json'],
            ['DS', 'mid_level', 'Mid Level Management', true, 11, 'json'],
            ['DS', 'sales_marketing', 'Sales & Marketing', true, 12, 'json'],
            ['DS', 'nama_perusahaan_induk', 'Nama Perusahaan Induk', true, 13, 'text'],
            ['DS', 'alamat_perusahaan_induk', 'Alamat Perusahaan Induk', true, 14, 'textarea'],

            // Manufacturer (MF)
            ['MF', 'nama_perusahaan', 'Nama Perusahaan', true, 1, 'text'],
            ['MF', 'npwp', 'NPWP', true, 2, 'text'],
            ['MF', 'tanggal_berdiri', 'Tanggal Berdiri', true, 3, 'date'],
            ['MF', 'tanggal_beroperasi', 'Tanggal Beroperasi', true, 4, 'date'],
            ['MF', 'alamat', 'Alamat', true, 5, 'textarea'],
            ['MF', 'phone', 'Telepon', true, 6, 'text'],
            ['MF', 'top_level', 'Top Level Management', true, 7, 'json'],
            ['MF', 'mid_level', 'Mid Level Management', true, 8, 'json'],
            ['MF', 'sales_marketing', 'Sales & Marketing', true, 9, 'json'],
            ['MF', 'nama_perusahaan_induk', 'Nama Perusahaan Induk', true, 10, 'text'],
        ];

        foreach ($mappings as $mapping) {
            DB::table('vendor_field_mappings')->insert([
                'tipe_perusahaan' => $mapping[0],
                'field_name' => $mapping[1],
                'field_label' => $mapping[2],
                'is_required' => $mapping[3],
                'field_order' => $mapping[4],
                'field_type' => $mapping[5],
                'field_options' => $this->getFieldOptions($mapping[1]),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Get field options untuk dropdown fields
     */
    private function getFieldOptions(string $fieldName): ?string
    {
        $options = [
            'bagian_grup' => ['ya', 'tidak'],
            'kepemilikan' => ['tidak', 'satu', 'lebih dari satu'],
            'kepemilikan_armada' => ['sewa', 'miliki pribadi'],
            'sumber_informasi' => ['website', 'instagram', 'facebook', 'rekomendasi', 'brosur', 'lainnya']
        ];

        return isset($options[$fieldName]) ? json_encode($options[$fieldName]) : null;
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendor_field_mappings');
    }
};