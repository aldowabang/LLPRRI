<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pegawais', function (Blueprint $table) {
            $table->id('id_pegawai');
            $table->foreignId('id_unit')->constrained('units', 'id_unit')->cascadeOnDelete();
            $table->foreignId('id_jabatan')->constrained('jabatans', 'id_jabatan')->cascadeOnDelete();
            $table->string('nip', 30)->unique();
            $table->string('nama_pegawai', 100);
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->string('no_hp', 20);
            $table->text('alamat');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pegawais');
    }
};
