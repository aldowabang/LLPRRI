<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['admin', 'pimpinan', 'pegawai'])->default('pegawai')->after('name');
            $table->foreignId('pegawai_id')->nullable()->constrained('pegawais', 'id_pegawai')->nullOnDelete()->after('role');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['pegawai_id']);
            $table->dropColumn(['role', 'pegawai_id']);
        });
    }
};
