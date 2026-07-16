<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kata_positif', function (Blueprint $table) {
            $table->id();
            $table->string('kata')->unique();
            $table->timestamps();
        });

        Schema::create('kata_negatif', function (Blueprint $table) {
            $table->id();
            $table->string('kata')->unique();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kata_negatif');
        Schema::dropIfExists('kata_positif');
    }
};