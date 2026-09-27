<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tugases', function (Blueprint $table) {
            $table->string('nomor_nota', 100)->nullable()->after('id_tugas');
            $table->string('disiarkan', 100)->nullable()->after('format');
            $table->time('waktu_rapat')->nullable()->after('tanggal_rapat');
            $table->string('tempat_rapat', 150)->nullable()->after('waktu_rapat');
            $table->time('waktu_produksi')->nullable()->after('tanggal_produksi');
            $table->string('tempat_produksi', 150)->nullable()->after('waktu_produksi');
            $table->string('narasumber', 255)->nullable()->after('topik');
            $table->string('penanggung_jawab', 150)->default('Kepala LPP RRI Kupang')->after('narasumber');
            $table->string('supervisor', 150)->default('Kabag TU dan Para Ketua Tim')->after('penanggung_jawab');
            $table->foreignId('id_pegawai')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('tugases', function (Blueprint $table) {
            $table->dropColumn([
                'nomor_nota',
                'disiarkan',
                'waktu_rapat',
                'tempat_rapat',
                'waktu_produksi',
                'tempat_produksi',
                'narasumber',
                'penanggung_jawab',
                'supervisor',
            ]);
            $table->foreignId('id_pegawai')->nullable(false)->change();
        });
    }
};
