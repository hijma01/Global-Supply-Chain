<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('skor_risiko', function (Blueprint $table) {
            $table->id();
            $table->foreignId('negara_id')->constrained('negara')->onDelete('cascade');
            $table->decimal('skor_cuaca', 6, 2)->default(0);
            $table->decimal('skor_inflasi', 6, 2)->default(0);
            $table->decimal('skor_kurs', 6, 2)->default(0);
            $table->decimal('skor_sentimen_berita', 6, 2)->default(0);
            $table->decimal('skor_total', 6, 2)->default(0);
            $table->enum('tingkat_risiko', ['rendah', 'sedang', 'tinggi'])->default('rendah');
            $table->timestamp('dihitung_pada');
            $table->timestamps();

            $table->index(['negara_id', 'dihitung_pada']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('skor_risiko');
    }
};