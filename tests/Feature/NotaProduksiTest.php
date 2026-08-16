<?php

namespace Tests\Feature;

use App\Models\Jabatan;
use App\Models\Pegawai;
use App\Models\Tugas;
use App\Models\TugasCrew;
use App\Models\Unit;
use Tests\TestCase;

class NotaProduksiTest extends TestCase
{
    public function test_can_create_tugas_with_nota_produksi_fields_and_crews(): void
    {
        $unit = Unit::create(['nama_unit' => 'Teknik']);
        $jabatan = Jabatan::create(['nama_jabatan' => 'Teknisi']);

        $pegawai1 = Pegawai::create([
            'id_unit' => $unit->id_unit,
            'id_jabatan' => $jabatan->id_jabatan,
            'nip' => '199001012020011001',
            'nama_pegawai' => 'Clara D. Amalo',
            'jenis_kelamin' => 'P',
            'no_hp' => '081234567890',
            'alamat' => 'Kupang',
        ]);

        $pegawai2 = Pegawai::create([
            'id_unit' => $unit->id_unit,
            'id_jabatan' => $jabatan->id_jabatan,
            'nip' => '199001012020011002',
            'nama_pegawai' => 'Ben Djara',
            'jenis_kelamin' => 'L',
            'no_hp' => '081234567891',
            'alamat' => 'Kupang',
        ]);

        $tugas = Tugas::create([
            'nomor_nota' => '1198/RRI.KPG/XVII.PPS.01.02/07/2025',
            'id_pegawai' => $pegawai1->id_pegawai,
            'nama_tugas' => 'Podcastkoe',
            'format' => 'Talkshow',
            'disiarkan' => 'Juli 2025',
            'tanggal_rapat' => '2025-07-21',
            'waktu_rapat' => '10:00:00',
            'tempat_rapat' => 'Ruang Siaran',
            'tanggal_produksi' => '2025-07-24',
            'waktu_produksi' => '13:00:00',
            'tempat_produksi' => 'Studio 1',
            'tempat' => 'Studio 1',
            'narasumber' => 'Aiptu Imelda Mella',
            'topik' => 'Kanker Mengajarkanku Lebih',
            'penanggung_jawab' => 'Kepala LPP RRI Kupang',
            'supervisor' => 'Kabag TU dan Para Ketua Tim',
            'status_tugas' => 'belum_mulai',
        ]);

        TugasCrew::create([
            'id_tugas' => $tugas->id_tugas,
            'id_pegawai' => $pegawai1->id_pegawai,
            'peran' => 'produser',
        ]);

        TugasCrew::create([
            'id_tugas' => $tugas->id_tugas,
            'id_pegawai' => $pegawai2->id_pegawai,
            'peran' => 'cameraman',
        ]);

        $this->assertDatabaseHas('tugases', [
            'id_tugas' => $tugas->id_tugas,
            'nomor_nota' => '1198/RRI.KPG/XVII.PPS.01.02/07/2025',
            'nama_tugas' => 'Podcastkoe',
        ]);

        $this->assertCount(2, $tugas->crews);
        $this->assertEquals('Clara D. Amalo', $tugas->getCrewNamesByRole('produser'));
        $this->assertEquals('Ben Djara', $tugas->getCrewNamesByRole('cameraman'));

        $pegawai1Tugases = Tugas::forPegawai($pegawai1->id_pegawai)->get();
        $this->assertCount(1, $pegawai1Tugases);

        $pegawai2Tugases = Tugas::forPegawai($pegawai2->id_pegawai)->get();
        $this->assertCount(1, $pegawai2Tugases);
    }
}
