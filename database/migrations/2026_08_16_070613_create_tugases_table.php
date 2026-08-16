<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tugases', function (Blueprint $table) {
            $table->id('id_tugas');
            $table->foreignId('id_pegawai')->constrained('pegawais', 'id_pegawai')->cascadeOnDelete();
            $table->string('nama_tugas', 150);
            $table->string('format', 50);
            $table->date('tanggal_produksi');
            $table->date('tanggal_rapat');
            $table->string('tempat', 150);
            $table->text('topik');
            $table->enum('status_tugas', ['belum_mulai', 'proses', 'selesai', 'acc'])->default('belum_mulai');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tugases');
    }
};
