<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\ManualBookPdfService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ManualBookPdfTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_download_manual_book_pdf(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get(route('manual-book.pdf'));

        $response->assertOk();
        $response->assertHeader('Content-Type', 'application/pdf');
        $this->assertStringStartsWith('%PDF', (string) $response->getContent());
    }

    public function test_pimpinan_can_download_manual_book_pdf(): void
    {
        $pimpinan = User::factory()->create(['role' => 'pimpinan']);

        $response = $this->actingAs($pimpinan)->get(route('manual-book.pdf'));

        $response->assertOk();
        $response->assertHeader('Content-Type', 'application/pdf');
    }

    public function test_pegawai_cannot_download_manual_book_pdf(): void
    {
        $pegawai = User::factory()->create(['role' => 'pegawai']);

        $response = $this->actingAs($pegawai)->get(route('manual-book.pdf'));

        $response->assertForbidden();
    }

    public function test_manual_book_service_reads_all_chapters(): void
    {
        $chapters = app(ManualBookPdfService::class)->chapters();

        $this->assertCount(count(ManualBookPdfService::MANIFEST), $chapters);
        $this->assertSame('Login', $chapters[1]['title']);
        $this->assertStringContainsString('screenshot-box', $chapters[1]['html']);
    }
}
