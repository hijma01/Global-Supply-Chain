<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('nilai_tukar', function (Blueprint $table) {
            $table->id();
            $table->string('mata_uang_dasar', 10)->default('USD');
            $table->string('mata_uang_tujuan', 10);
            $table->decimal('nilai_kurs', 20, 6);
            $table->timestamp('dicatat_pada');
            $table->timestamps();

            $table->unique(['mata_uang_dasar', 'mata_uang_tujuan', 'dicatat_pada'], 'unik_snapshot_kurs');
        });

        Schema::create('riwayat_nilai_tukar', function (Blueprint $table) {
            $table->id();
            $table->string('kode_mata_uang', 10);
            $table->decimal('nilai_kurs', 20, 6);
            $table->date('tanggal');
            $table->timestamps();

            $table->index(['kode_mata_uang', 'tanggal']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('riwayat_nilai_tukar');
        Schema::dropIfExists('nilai_tukar');
    }
};