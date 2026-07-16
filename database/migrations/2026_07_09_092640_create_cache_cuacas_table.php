<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cache_cuaca', function (Blueprint $table) {
            $table->id();
            $table->foreignId('negara_id')->constrained('negara')->onDelete('cascade');
            $table->decimal('suhu', 6, 2)->nullable()->comment('Celcius');
            $table->decimal('curah_hujan', 8, 2)->nullable()->comment('mm');
            $table->decimal('kecepatan_angin', 6, 2)->nullable()->comment('km/jam');
            $table->enum('tingkat_risiko_badai', ['rendah', 'sedang', 'tinggi'])->default('rendah');
            $table->timestamp('dicatat_pada');
            $table->timestamps();

            $table->index(['negara_id', 'dicatat_pada']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cache_cuaca');
    }
};