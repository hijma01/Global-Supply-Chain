<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cache_berita', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->text('deskripsi')->nullable();
            $table->string('url')->unique();
            $table->string('sumber')->nullable();
            $table->enum('kategori', ['ekonomi', 'logistik', 'geopolitik', 'lainnya'])->default('lainnya');
            $table->timestamp('diterbitkan_pada')->nullable();
            $table->unsignedTinyInteger('skor_positif')->default(0);
            $table->unsignedTinyInteger('skor_negatif')->default(0);
            $table->enum('label_sentimen', ['positif', 'netral', 'negatif'])->default('netral');
            $table->timestamps();

            $table->index(['kategori', 'diterbitkan_pada']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cache_berita');
    }
};