<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('data_ekonomi_negara', function (Blueprint $table) {
            $table->id();
            $table->foreignId('negara_id')->constrained('negara')->onDelete('cascade');
            $table->decimal('pdb', 20, 2)->nullable()->comment('GDP / Produk Domestik Bruto');
            $table->decimal('tingkat_inflasi', 8, 2)->nullable();
            $table->decimal('nilai_ekspor', 20, 2)->nullable();
            $table->decimal('nilai_impor', 20, 2)->nullable();
            $table->unsignedSmallInteger('tahun');
            $table->timestamps();

            $table->unique(['negara_id', 'tahun']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('data_ekonomi_negara');
    }
};