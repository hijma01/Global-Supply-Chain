<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('negara', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('kode_negara', 2)->unique()->comment('Kode ISO Alpha-2, contoh: ID');
            $table->string('kode_negara_3', 3)->nullable()->unique()->comment('Kode ISO Alpha-3, contoh: IDN');
            $table->string('kode_mata_uang', 10)->nullable();
            $table->string('nama_mata_uang')->nullable();
            $table->string('wilayah')->nullable();
            $table->string('sub_wilayah')->nullable();
            $table->string('ibu_kota')->nullable();
            $table->unsignedBigInteger('populasi')->nullable();
            $table->string('url_bendera')->nullable();
            $table->decimal('lintang', 10, 6)->nullable()->comment('Latitude');
            $table->decimal('bujur', 10, 6)->nullable()->comment('Longitude');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('negara');
    }
};