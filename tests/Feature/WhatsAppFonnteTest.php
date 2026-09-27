<?php

namespace Tests\Feature;

use App\Models\Jabatan;
use App\Models\Pegawai;
use App\Models\Tugas;
use App\Models\TugasCrew;
use App\Models\Unit;
use App\Models\User;
use App\Services\WhatsAppService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class WhatsAppFonnteTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        config([
            'services.fonnte.url' => 'https://api.fonnte.com/send',
            'services.fonnte.token' => 'test-token',
            'services.fonnte.country_code' => '62',
            'services.fonnte.delay' => '2',
        ]);
    }

    public function test_send_tugas_baru_posts_to_fonnte_with_plain_number(): void
    {
        Http::fake([
            'api.fonnte.com/*' => Http::response(['status' => true, 'message' => 'ok'], 200),
        ]);

        [$pegawai, $tugas] = $this->makeTugasWithPegawai('081234567890');

        $result = app(WhatsAppService::class)->sendTugasBaru($tugas, $pegawai, 'produser');

        $this->assertTrue($result);

        Http::assertSent(function ($request) {
            return $request->url() === 'https://api.fonnte.com/send'
                && $request->header('Authorization') === ['test-token']
                && $request['target'] === '6281234567890'
                && str_contains((string) $request['message'], 'Podcastkoe')
                && $request['countryCode'] === '62'
                && $request['delay'] === '2';
        });
    }

    public function test_send_returns_false_without_api_key(): void
    {
        config(['services.fonnte.token' => null]);
        Http::fake();

        [$pegawai, $tugas] = $this->makeTugasWithPegawai('081234567890');

        $result = app(WhatsAppService::class)->sendTugasBaru($tugas, $pegawai);

        $this->assertFalse($result);
        Http::assertNothingSent();
    }

    public function test_send_skips_pegawai_without_phone_number(): void
    {
        Http::fake();

        [$pegawai, $tugas] = $this->makeTugasWithPegawai('');
        $pegawai->no_hp = '';
        $pegawai->save();

        $result = app(WhatsAppService::class)->sendTugasBaru($tugas, $pegawai);

        $this->assertFalse($result);
        Http::assertNothingSent();
    }

    public function test_broadcast_sends_once_per_unique_pegawai(): void
    {
        Http::fake([
            'api.fonnte.com/*' => Http::response(['status' => true], 200),
        ]);

        [$pegawai, $tugas] = $this->makeTugasWithPegawai('081234567890');

        // Same pegawai twice as crew + as primary must only be notified once.
        TugasCrew::create(['id_tugas' => $tugas->id_tugas, 'id_pegawai' => $pegawai->id_pegawai, 'peran' => 'produser']);
        TugasCrew::create(['id_tugas' => $tugas->id_tugas, 'id_pegawai' => $pegawai->id_pegawai, 'peran' => 'cameraman']);

        app(WhatsAppService::class)->broadcastNotaProduksi($tugas);

        Http::assertSentCount(1);
    }

    public function test_send_tugas_selesai_notifies_via_fonnte(): void
    {
        Http::fake([
            'api.fonnte.com/*' => Http::response(['status' => true], 200),
        ]);

        [$pegawai, $tugas] = $this->makeTugasWithPegawai('081234567890');

        $pimpinan = User::create([
            'name' => 'Pimpinan',
            'email' => 'pimpinan@example.com',
            'password' => 'password',
        ]);

        $result = app(WhatsAppService::class)->sendTugasSelesai($tugas, $pimpinan);

        $this->assertTrue($result);
        Http::assertSent(function ($request) {
            return $request['target'] === '6281234567890'
                && str_contains((string) $request['message'], 'Podcastkoe');
        });
    }

    /**
     * @return array{0: Pegawai, 1: Tugas}
     */
    protected function makeTugasWithPegawai(string $noHp): array
    {
        $unit = Unit::create(['nama_unit' => 'Teknik']);
        $jabatan = Jabatan::create(['nama_jabatan' => 'Teknisi']);

        $pegawai = Pegawai::create([
            'id_unit' => $unit->id_unit,
            'id_jabatan' => $jabatan->id_jabatan,
            'nip' => '199001012020011001',
            'nama_pegawai' => 'Clara D. Amalo',
            'jenis_kelamin' => 'P',
            'no_hp' => $noHp === '' ? '-' : $noHp,
            'alamat' => 'Kupang',
        ]);

        $tugas = Tugas::create([
            'nomor_nota' => '1198/RRI.KPG/XVII.PPS.01.02/07/2025',
            'id_pegawai' => $pegawai->id_pegawai,
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

        return [$pegawai, $tugas];
    }
}
