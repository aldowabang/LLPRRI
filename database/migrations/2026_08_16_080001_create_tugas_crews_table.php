<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tugas_crews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_tugas')->constrained('tugases', 'id_tugas')->cascadeOnDelete();
            $table->foreignId('id_pegawai')->constrained('pegawais', 'id_pegawai')->cascadeOnDelete();
            $table->string('peran', 50);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tugas_crews');
    }
};
