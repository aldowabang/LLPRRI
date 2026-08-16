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
        $unit5 = Unit::create(['nama_unit' => 'Stasiun Radio']);
        $unit6 = Unit::create(['nama_unit' => 'Stasiun TV']);

        // Create Jabatans
        $jabatan1 = Jabatan::create(['nama_jabatan' => 'Kepala Bidang']);
        $jabatan2 = Jabatan::create(['nama_jabatan' => 'Kepala Seksi']);
        $jabatan3 = Jabatan::create(['nama_jabatan' => 'Penyiar']);
        $jabatan4 = Jabatan::create(['nama_jabatan' => 'Teknisi']);
        $jabatan5 = Jabatan::create(['nama_jabatan' => 'Staf']);
        $jabatan6 = Jabatan::create(['nama_jabatan' => 'Produser']);
        $jabatan7 = Jabatan::create(['nama_jabatan' => 'Cameraman']);
        $jabatan8 = Jabatan::create(['nama_jabatan' => 'Editor']);

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

        // Create Pegawai with various roles for crew assignment
        $pegawaiData = [
            // Bidang Program
            [
                'id_unit' => $unit1->id_unit,
                'id_jabatan' => $jabatan6->id_jabatan,
                'nip' => '199001012020011010',
                'nama_pegawai' => 'Hendra Kurniawan',
                'jenis_kelamin' => 'L',
                'no_hp' => '081234567801',
                'alamat' => 'Jl. Sudirman No. 12, Kupang',
            ],
            [
                'id_unit' => $unit1->id_unit,
                'id_jabatan' => $jabatan5->id_jabatan,
                'nip' => '199201012020012011',
                'nama_pegawai' => 'Rina Sari',
                'jenis_kelamin' => 'P',
                'no_hp' => '081234567802',
                'alamat' => 'Jl. Pahlawan No. 15, Kupang',
            ],
            [
                'id_unit' => $unit1->id_unit,
                'id_jabatan' => $jabatan3->id_jabatan,
                'nip' => '199301012020012012',
                'nama_pegawai' => 'Dewi Lestari',
                'jenis_kelamin' => 'P',
                'no_hp' => '081234567803',
                'alamat' => 'Jl. Gajah Mada No. 5, Kupang',
            ],
            // Bidang Berita
            [
                'id_unit' => $unit2->id_unit,
                'id_jabatan' => $jabatan3->id_jabatan,
                'nip' => '199501012020012013',
                'nama_pegawai' => 'Siti Aminah',
                'jenis_kelamin' => 'P',
                'no_hp' => '081234567804',
                'alamat' => 'Jl. Ahmad Yani No. 20, Kupang',
            ],
            [
                'id_unit' => $unit2->id_unit,
                'id_jabatan' => $jabatan3->id_jabatan,
                'nip' => '199401012020011014',
                'nama_pegawai' => 'Rahmatulloh',
                'jenis_kelamin' => 'L',
                'no_hp' => '081234567805',
                'alamat' => 'Jl. Timor Raya No. 8, Kupang',
            ],
            [
                'id_unit' => $unit2->id_unit,
                'id_jabatan' => $jabatan5->id_jabatan,
                'nip' => '199601012020012015',
                'nama_pegawai' => 'Nita Putri',
                'jenis_kelamin' => 'P',
                'no_hp' => '081234567806',
                'alamat' => 'Jl. El Tari No. 25, Kupang',
            ],
            // Bidang Teknik
            [
                'id_unit' => $unit3->id_unit,
                'id_jabatan' => $jabatan4->id_jabatan,
                'nip' => '199101012020011016',
                'nama_pegawai' => 'Andi Wijaya',
                'jenis_kelamin' => 'L',
                'no_hp' => '081234567807',
                'alamat' => 'Jl. Veteran No. 30, Kupang',
            ],
            [
                'id_unit' => $unit3->id_unit,
                'id_jabatan' => $jabatan4->id_jabatan,
                'nip' => '199301012020011017',
                'nama_pegawai' => 'Yusuf Mandala',
                'jenis_kelamin' => 'L',
                'no_hp' => '081234567808',
                'alamat' => 'Jl. Kartini No. 18, Kupang',
            ],
            [
                'id_unit' => $unit3->id_unit,
                'id_jabatan' => $jabatan7->id_jabatan,
                'nip' => '199701012020011018',
                'nama_pegawai' => 'Fajar Nugroho',
                'jenis_kelamin' => 'L',
                'no_hp' => '081234567809',
                'alamat' => 'Jl. Sisingamangaraja No. 7, Kupang',
            ],
            [
                'id_unit' => $unit3->id_unit,
                'id_jabatan' => $jabatan7->id_jabatan,
                'nip' => '199801012020012019',
                'nama_pegawai' => 'Anisa Rahmawati',
                'jenis_kelamin' => 'P',
                'no_hp' => '081234567810',
                'alamat' => 'Jl. Diponegoro No. 22, Kupang',
            ],
            // Stasiun Radio
            [
                'id_unit' => $unit5->id_unit,
                'id_jabatan' => $jabatan6->id_jabatan,
                'nip' => '198901012020011020',
                'nama_pegawai' => 'Dwi Prasetyo',
                'jenis_kelamin' => 'L',
                'no_hp' => '081234567811',
                'alamat' => 'Jl. Thamrin No. 9, Kupang',
            ],
            [
                'id_unit' => $unit5->id_unit,
                'id_jabatan' => $jabatan3->id_jabatan,
                'nip' => '199601012020012021',
                'nama_pegawai' => 'Maya Sari',
                'jenis_kelamin' => 'P',
                'no_hp' => '081234567812',
                'alamat' => 'Jl. Mangkubumi No. 14, Kupang',
            ],
            [
                'id_unit' => $unit5->id_unit,
                'id_jabatan' => $jabatan8->id_jabatan,
                'nip' => '199701012020011022',
                'nama_pegawai' => 'Rizky Pratama',
                'jenis_kelamin' => 'L',
                'no_hp' => '081234567813',
                'alamat' => 'Jl. Asia Afrika No. 11, Kupang',
            ],
            // Stasiun TV
            [
                'id_unit' => $unit6->id_unit,
                'id_jabatan' => $jabatan7->id_jabatan,
                'nip' => '199401012020011023',
                'nama_pegawai' => 'Arif Setiawan',
                'jenis_kelamin' => 'L',
                'no_hp' => '081234567814',
                'alamat' => 'Jl. Senopati No. 16, Kupang',
            ],
            [
                'id_unit' => $unit6->id_unit,
                'id_jabatan' => $jabatan8->id_jabatan,
                'nip' => '199501012020012024',
                'nama_pegawai' => 'Lestari Wulan',
                'jenis_kelamin' => 'P',
                'no_hp' => '081234567815',
                'alamat' => 'Jl. Sultan Agung No. 21, Kupang',
            ],
            [
                'id_unit' => $unit6->id_unit,
                'id_jabatan' => $jabatan5->id_jabatan,
                'nip' => '199901012020012025',
                'nama_pegawai' => 'Putri Amelia',
                'jenis_kelamin' => 'P',
                'no_hp' => '081234567816',
                'alamat' => 'Jl. Pemuda No. 33, Kupang',
            ],
            [
                'id_unit' => $unit1->id_unit,
                'id_jabatan' => $jabatan2->id_jabatan,
                'nip' => '198801012020011026',
                'nama_pegawai' => 'Irwan Hakim',
                'jenis_kelamin' => 'L',
                'no_hp' => '081234567817',
                'alamat' => 'Jl. Wahidin No. 28, Kupang',
            ],
            [
                'id_unit' => $unit4->id_unit,
                'id_jabatan' => $jabatan5->id_jabatan,
                'nip' => '200001012020012028',
                'nama_pegawai' => 'Citra Dewi',
                'jenis_kelamin' => 'P',
                'no_hp' => '081234567818',
                'alamat' => 'Jl. Kenari No. 4, Kupang',
            ],
        ];

        $createdPegawai = [];
        foreach ($pegawaiData as $data) {
            $createdPegawai[] = Pegawai::create($data);
        }

        // Create Pegawai user (for login testing)
        User::create([
            'name' => $createdPegawai[3]->nama_pegawai,
            'email' => 'pegawai@rri.test',
            'password' => 'password',
            'role' => 'pegawai',
            'pegawai_id' => $createdPegawai[3]->id_pegawai,
            'email_verified_at' => now(),
        ]);
    }
}
