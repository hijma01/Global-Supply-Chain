<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('perbandingan_negara', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengguna_id')->nullable()->constrained('pengguna')->onDelete('cascade');
            $table->foreignId('negara_a_id')->constrained('negara')->onDelete('cascade');
            $table->foreignId('negara_b_id')->constrained('negara')->onDelete('cascade');
            $table->timestamp('dibandingkan_pada');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('perbandingan_negara');
    }
};