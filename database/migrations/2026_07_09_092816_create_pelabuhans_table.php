<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pelabuhan', function (Blueprint $table) {
            $table->id();
            $table->string('nama_pelabuhan');
            $table->foreignId('negara_id')->nullable()->constrained('negara')->onDelete('set null');
            $table->decimal('lintang', 10, 6);
            $table->decimal('bujur', 10, 6);
            $table->string('ukuran_pelabuhan')->nullable();
            $table->string('tipe_pelabuhan')->nullable();
            $table->timestamps();

            $table->index('nama_pelabuhan');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pelabuhan');
    }
};