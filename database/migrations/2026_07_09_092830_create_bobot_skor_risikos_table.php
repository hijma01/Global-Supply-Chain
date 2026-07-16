<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bobot_skor_risiko', function (Blueprint $table) {
            $table->id();
            $table->decimal('bobot_cuaca', 5, 2)->default(30.00)->comment('Persen bobot cuaca');
            $table->decimal('bobot_inflasi', 5, 2)->default(20.00)->comment('Persen bobot inflasi');
            $table->decimal('bobot_berita', 5, 2)->default(40.00)->comment('Persen bobot berita/politik');
            $table->decimal('bobot_kurs', 5, 2)->default(10.00)->comment('Persen bobot kurs mata uang');
            $table->timestamps();
        });

        // Isi nilai bobot default agar mesin skor risiko langsung bisa dipakai
        \Illuminate\Support\Facades\DB::table('bobot_skor_risiko')->insert([
            'bobot_cuaca' => 30.00,
            'bobot_inflasi' => 20.00,
            'bobot_berita' => 40.00,
            'bobot_kurs' => 10.00,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('bobot_skor_risiko');
    }
};