<?php

namespace Tests\Feature;

use App\Models\Jabatan;
use App\Models\Pegawai;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserPdfTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_download_user_data_pdf(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get(route('admin.users.pdf'));

        $response->assertOk();
        $response->assertHeader('Content-Type', 'application/pdf');
        $this->assertStringStartsWith('%PDF', (string) $response->getContent());
    }

    public function test_non_admin_cannot_download_user_data_pdf(): void
    {
        $pimpinan = User::factory()->create(['role' => 'pimpinan']);

        $this->actingAs($pimpinan)->get(route('admin.users.pdf'))->assertForbidden();

        $pegawai = User::factory()->create(['role' => 'pegawai']);

        $this->actingAs($pegawai)->get(route('admin.users.pdf'))->assertForbidden();
    }

    public function test_user_pdf_view_shows_complete_columns_without_password(): void
    {
        $user = new User([
            'name' => 'Budi Santoso',
            'email' => 'budi@example.com',
            'role' => 'pimpinan',
        ]);
        $pegawai = new Pegawai(['nama_pegawai' => 'Budi Santoso', 'nip' => '199001012020011001']);
        $pegawai->setRelation('unit', new Unit(['nama_unit' => 'Pemberitaan']));
        $pegawai->setRelation('jabatan', new Jabatan(['nama_jabatan' => 'Produser']));
        $user->setRelation('pegawai', $pegawai);

        $html = view('livewire.admin.laporan.users-pdf', ['users' => collect([$user])])->render();

        $this->assertStringContainsString('Budi Santoso', $html);
        $this->assertStringContainsString('budi@example.com', $html);
        $this->assertStringContainsString('Pimpinan', $html);
        $this->assertStringContainsString('199001012020011001', $html);
        $this->assertStringContainsString('Pemberitaan', $html);
        $this->assertStringContainsString('Produser', $html);
        $this->assertStringNotContainsStringIgnoringCase('password', $html);
    }
}
