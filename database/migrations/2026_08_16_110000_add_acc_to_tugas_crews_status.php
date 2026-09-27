<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tugas_crews', function (Blueprint $table) {
            $table->enum('status', ['belum_mulai', 'proses', 'selesai', 'acc'])->default('belum_mulai')->change();
        });
    }

    public function down(): void
    {
        Schema::table('tugas_crews', function (Blueprint $table) {
            $table->enum('status', ['belum_mulai', 'proses', 'selesai'])->default('belum_mulai')->change();
        });
    }
};
