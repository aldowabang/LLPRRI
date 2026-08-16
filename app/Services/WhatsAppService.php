<?php

namespace App\Services;

use App\Models\Pegawai;
use App\Models\Tugas;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    protected string $baseUrl;

    protected string $session;

    public function __construct()
    {
        $this->baseUrl = config('services.waha.url', 'http://localhost:3000');
        $this->session = config('services.waha.session', 'default');
    }

    public function sendTugasBaru(Tugas $tugas, Pegawai $pegawai, ?string $peran = null): bool
    {
        $phone = $this->formatPhone($pegawai->no_hp);
        $peranText = $peran ? ' (Peran: *'.ucfirst(str_replace('_', ' ', $peran)).'*)' : '';

        $message = "📋 *Nota Produksi / Tugas Baru LPP RRI Kupang*\n\n"
            ."Halo {$pegawai->nama_pegawai},\n"
            ."Anda telah ditugaskan dalam Nota Produksi{$peranText}:\n\n"
            ."*{$tugas->nama_tugas}*\n"
            .'• No Nota: '.($tugas->nomor_nota ?? '-')."\n"
            ."• Format: {$tugas->format}\n"
            .'• Tanggal Pra-Produksi: '.($tugas->tanggal_rapat ? $tugas->tanggal_rapat->format('d/m/Y') : '-').($tugas->waktu_rapat ? ' jam '.substr($tugas->waktu_rapat, 0, 5) : '')."\n"
            .'• Tanggal Produksi: '.($tugas->tanggal_produksi ? $tugas->tanggal_produksi->format('d/m/Y') : '-').($tugas->waktu_produksi ? ' jam '.substr($tugas->waktu_produksi, 0, 5) : '')."\n"
            .'• Tempat: '.($tugas->tempat_produksi ?? $tugas->tempat ?? '-')."\n"
            ."• Topik: {$tugas->topik}\n\n"
            .'Silakan buka aplikasi untuk melihat detail tugas selengkapnya.';

        return $this->sendMessage($phone, $message);
    }

    public function broadcastNotaProduksi(Tugas $tugas): void
    {
        $tugas->loadMissing(['crews.pegawai', 'pegawai']);

        $sentPegawaiIds = [];

        // Notify crew members
        foreach ($tugas->crews as $crew) {
            if ($crew->pegawai && ! in_array($crew->id_pegawai, $sentPegawaiIds)) {
                $this->sendTugasBaru($tugas, $crew->pegawai, $crew->peran);
                $sentPegawaiIds[] = $crew->id_pegawai;
            }
        }

        // Notify single assigned pegawai if not already sent
        if ($tugas->pegawai && ! in_array($tugas->id_pegawai, $sentPegawaiIds)) {
            $this->sendTugasBaru($tugas, $tugas->pegawai);
        }
    }

    public function sendTugasSelesai(Tugas $tugas, User $pimpinan): bool
    {
        $phone = $this->formatPhone($tugas->pegawai->no_hp);

        $message = "✅ *Konfirmasi Tugas Selesai*\n\n"
            ."Halo {$pimpinan->name},\n"
            ."Tugas berikut telah diselesaikan oleh {$tugas->pegawai->nama_pegawai}:\n\n"
            ."*{$tugas->nama_tugas}*\n"
            ."• Format: {$tugas->format}\n"
            ."• Tanggal Produksi: {$tugas->tanggal_produksi->format('d/m/Y')}\n\n"
            .'Silakan buka aplikasi untuk melihat dan meng-ACC tugas.';

        return $this->sendMessage($phone, $message);
    }

    protected function sendMessage(string $phone, string $message): bool
    {
        try {
            $response = Http::timeout(5)
                ->post("{$this->baseUrl}/api/{$this->session}/send-text", [
                    'chatId' => $phone,
                    'text' => $message,
                ]);

            if ($response->successful()) {
                Log::info("WhatsApp message sent to {$phone}");

                return true;
            }

            Log::error('WhatsApp API error', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return false;
        } catch (\Exception $e) {
            Log::error('WhatsApp send failed', [
                'phone' => $phone,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }

    protected function formatPhone(string $phone): string
    {
        $phone = preg_replace('/[^0-9]/', '', $phone);

        if (str_starts_with($phone, '0')) {
            $phone = '62'.substr($phone, 1);
        } elseif (! str_starts_with($phone, '62')) {
            $phone = '62'.$phone;
        }

        return $phone.'@c.us';
    }
}
