<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('manufactures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vendor_id')->constrained()->onDelete('cascade');
            $table->json('product_types')->nullable();
            $table->json('personnel')->nullable();
            $table->json('plants')->nullable();
            $table->json('after_sales')->nullable();
            $table->json('engineering_design')->nullable();
            $table->json('inventory')->nullable();
            $table->text('subcontracting')->nullable();
            $table->json('code_standard')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('manufactures');
    }
};
