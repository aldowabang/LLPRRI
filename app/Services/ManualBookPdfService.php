<?php

namespace App\Services;

use Illuminate\Support\Facades\File;
use League\CommonMark\GithubFlavoredMarkdownConverter;

class ManualBookPdfService
{
    /**
     * Daftar bab manual book sesuai urutan cetak.
     *
     * @var array<int, array{section: string, file: string}>
     */
    public const MANIFEST = [
        ['section' => 'Pengantar', 'file' => 'README.md'],
        ['section' => 'Bab 1 — Halaman Umum', 'file' => '00-umum/01-login.md'],
        ['section' => 'Bab 1 — Halaman Umum', 'file' => '00-umum/02-welcome-dan-redirect-dashboard.md'],
        ['section' => 'Bab 1 — Halaman Umum', 'file' => '00-umum/03-pengaturan-profil.md'],
        ['section' => 'Bab 2 — Admin', 'file' => '01-admin/01-dashboard.md'],
        ['section' => 'Bab 2 — Admin', 'file' => '01-admin/02-data-unit.md'],
        ['section' => 'Bab 2 — Admin', 'file' => '01-admin/03-data-jabatan.md'],
        ['section' => 'Bab 2 — Admin', 'file' => '01-admin/04-data-pegawai.md'],
        ['section' => 'Bab 2 — Admin', 'file' => '01-admin/05-data-user.md'],
        ['section' => 'Bab 3 — Pimpinan', 'file' => '02-pimpinan/01-dashboard.md'],
        ['section' => 'Bab 3 — Pimpinan', 'file' => '02-pimpinan/02-data-pegawai.md'],
        ['section' => 'Bab 3 — Pimpinan', 'file' => '02-pimpinan/03-daftar-tugas.md'],
        ['section' => 'Bab 3 — Pimpinan', 'file' => '02-pimpinan/04-buat-tugas.md'],
        ['section' => 'Bab 3 — Pimpinan', 'file' => '02-pimpinan/05-detail-dan-validasi-tugas.md'],
        ['section' => 'Bab 3 — Pimpinan', 'file' => '02-pimpinan/06-nota-produksi-pdf.md'],
        ['section' => 'Bab 3 — Pimpinan', 'file' => '02-pimpinan/07-laporan-pdf.md'],
        ['section' => 'Bab 4 — Pegawai', 'file' => '03-pegawai/01-dashboard.md'],
        ['section' => 'Bab 4 — Pegawai', 'file' => '03-pegawai/02-daftar-tugas.md'],
        ['section' => 'Bab 4 — Pegawai', 'file' => '03-pegawai/03-detail-dan-update-status.md'],
        ['section' => 'Bab 4 — Pegawai', 'file' => '03-pegawai/04-nota-produksi-pdf.md'],
        ['section' => 'Lampiran', 'file' => '04-lampiran/status-tugas-dan-crew.md'],
        ['section' => 'Lampiran', 'file' => '04-lampiran/skema-database.md'],
    ];

    protected GithubFlavoredMarkdownConverter $converter;

    public function __construct()
    {
        $this->converter = new GithubFlavoredMarkdownConverter([
            'html_input' => 'strip',
            'allow_unsafe_links' => false,
        ]);
    }

    /**
     * Baca seluruh bab manual book dan ubah Markdown menjadi HTML siap cetak.
     *
     * @return array<int, array{section: string, title: string, html: string}>
     */
    public function chapters(): array
    {
        $chapters = [];

        foreach (self::MANIFEST as $entry) {
            $path = $this->basePath().'/'.$entry['file'];

            if (! File::exists($path)) {
                continue;
            }

            $markdown = File::get($path);

            $chapters[] = [
                'section' => $entry['section'],
                'title' => $this->extractTitle($markdown),
                'html' => $this->screenshotBoxes(
                    (string) $this->converter->convert($this->stripTitle($markdown))
                ),
            ];
        }

        return $chapters;
    }

    public function basePath(): string
    {
        return base_path('docs/manual-book');
    }

    protected function extractTitle(string $markdown): string
    {
        foreach (explode("\n", $markdown) as $line) {
            if (str_starts_with(trim($line), '# ')) {
                return trim(substr(trim($line), 2));
            }
        }

        return 'Tanpa Judul';
    }

    protected function stripTitle(string $markdown): string
    {
        $lines = explode("\n", $markdown);

        foreach ($lines as $index => $line) {
            if (str_starts_with(trim($line), '# ')) {
                unset($lines[$index]);
                break;
            }
        }

        return implode("\n", $lines);
    }

    protected function screenshotBoxes(string $html): string
    {
        $result = preg_replace(
            '#<blockquote>\s*<p>(.*?)</p>\s*</blockquote>#s',
            '<div class="screenshot-box">$1</div>',
            $html
        );

        return is_string($result) ? $result : $html;
    }
}
