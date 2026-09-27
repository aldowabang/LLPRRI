<?php

namespace App\Livewire\Pegawai\Tugas;

use App\Models\Tugas;
use App\Models\TugasCrew;
use App\Models\User;
use App\Services\WhatsAppService;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class Show extends Component
{
    public Tugas $tugas;

    public ?TugasCrew $myCrew = null;

    public function mount($id_tugas): void
    {
        $this->tugas = Tugas::with(['pegawai', 'crews.pegawai'])->findOrFail($id_tugas);

        $pegawai = auth()->user()->pegawai;
        if ($pegawai) {
            $this->myCrew = TugasCrew::where('id_tugas', $this->tugas->id_tugas)
                ->where('id_pegawai', $pegawai->id_pegawai)
                ->first();
        }
    }

    public function mulai(): void
    {
        if ($this->myCrew && $this->myCrew->status === 'belum_mulai') {
            $this->myCrew->update(['status' => 'proses']);
            $this->myCrew->refresh();
            session()->flash('success', 'Tugas Anda telah dimulai.');
        }
    }

    public function selesai(): void
    {
        if ($this->myCrew && $this->myCrew->status === 'proses') {
            $this->myCrew->update(['status' => 'selesai']);
            $this->myCrew->refresh();

            $pimpinan = User::where('role', 'pimpinan')->first();
            if ($pimpinan) {
                try {
                    app(WhatsAppService::class)->sendTugasSelesai($this->tugas, $pimpinan);
                } catch (\Exception $e) {
                    Log::warning('WhatsApp notification failed', [
                        'error' => $e->getMessage(),
                    ]);
                }
            }

            session()->flash('success', 'Tugas Anda telah selesai. Menunggu ACC dari pimpinan.');
        }
    }

    public function render()
    {
        return view('livewire.pegawai.tugas.show');
    }
}
