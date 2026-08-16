<?php

namespace Database\Seeders;

use App\Models\Jabatan;
use App\Models\Pegawai;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create Units
        $unit1 = Unit::create(['nama_unit' => 'Bidang Program']);
        $unit2 = Unit::create(['nama_unit' => 'Bidang Berita']);
        $unit3 = Unit::create(['nama_unit' => 'Bidang Teknik']);
        $unit4 = Unit::create(['nama_unit' => 'Bidang Umum']);

        // Create Jabatans
        $jabatan1 = Jabatan::create(['nama_jabatan' => 'Kepala Bidang']);
        $jabatan2 = Jabatan::create(['nama_jabatan' => 'Kepala Seksi']);
        $jabatan3 = Jabatan::create(['nama_jabatan' => 'Penyiar']);
        $jabatan4 = Jabatan::create(['nama_jabatan' => 'Teknisi']);
        $jabatan5 = Jabatan::create(['nama_jabatan' => 'Staf']);

        // Create Admin (no pegawai)
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@rri.test',
            'password' => 'password',
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        // Create Pimpinan
        $pegawaiPimpinan = Pegawai::create([
            'id_unit' => $unit1->id_unit,
            'id_jabatan' => $jabatan1->id_jabatan,
            'nip' => '198501012010011001',
            'nama_pegawai' => 'Budi Santoso',
            'jenis_kelamin' => 'L',
            'no_hp' => '081234567890',
            'alamat' => 'Jl. Merdeka No. 1, Kupang',
        ]);

        User::create([
            'name' => 'Budi Santoso',
            'email' => 'pimpinan@rri.test',
            'password' => 'password',
            'role' => 'pimpinan',
            'pegawai_id' => $pegawaiPimpinan->id_pegawai,
            'email_verified_at' => now(),
        ]);

        // Create Pegawai
        $pegawai1 = Pegawai::create([
            'id_unit' => $unit2->id_unit,
            'id_jabatan' => $jabatan3->id_jabatan,
            'nip' => '199501012020012001',
            'nama_pegawai' => 'Siti Aminah',
            'jenis_kelamin' => 'P',
            'no_hp' => '081234567891',
            'alamat' => 'Jl. Sudirman No. 10, Kupang',
        ]);

        User::create([
            'name' => 'Siti Aminah',
            'email' => 'pegawai@rri.test',
            'password' => 'password',
            'role' => 'pegawai',
            'pegawai_id' => $pegawai1->id_pegawai,
            'email_verified_at' => now(),
        ]);

        Pegawai::create([
            'id_unit' => $unit2->id_unit,
            'id_jabatan' => $jabatan3->id_jabatan,
            'nip' => '199601012020012002',
            'nama_pegawai' => 'Dewi Lestari',
            'jenis_kelamin' => 'P',
            'no_hp' => '081234567892',
            'alamat' => 'Jl. Gajah Mada No. 5, Kupang',
        ]);

        Pegawai::create([
            'id_unit' => $unit3->id_unit,
            'id_jabatan' => $jabatan4->id_jabatan,
            'nip' => '199701012020011003',
            'nama_pegawai' => 'Andi Wijaya',
            'jenis_kelamin' => 'L',
            'no_hp' => '081234567893',
            'alamat' => 'Jl. Ahmad Yani No. 20, Kupang',
        ]);

        Pegawai::create([
            'id_unit' => $unit1->id_unit,
            'id_jabatan' => $jabatan5->id_jabatan,
            'nip' => '199801012020012004',
            'nama_pegawai' => 'Rina Sari',
            'jenis_kelamin' => 'P',
            'no_hp' => '081234567894',
            'alamat' => 'Jl. Pahlawan No. 15, Kupang',
        ]);
    }
}
