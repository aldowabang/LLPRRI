<?php
namespace App\Livewire\Pegawai\Tugas;

use App\Models\Tugas;
use App\Models\User;
use App\Services\WhatsAppService;
use Livewire\Component;

class Show extends Component
{
    public Tugas $tugas;

    public function mount($id_tugas)
    {
        $this->tugas = Tugas::with('pegawai')->findOrFail($id_tugas);
    }

    public function mulai()
    {
        if ($this->tugas->status_tugas === 'belum_mulai') {
            $this->tugas->update(['status_tugas' => 'proses']);
            $this->tugas->refresh();
            session()->flash('success', 'Tugas telah dimulai.');
        }
    }

    public function selesai()
    {
        if ($this->tugas->status_tugas === 'proses') {
            $this->tugas->update(['status_tugas' => 'selesai']);
            $this->tugas->refresh();

            $pimpinan = User::where('role', 'pimpinan')->first();
            if ($pimpinan) {
                try {
                    app(WhatsAppService::class)->sendTugasSelesai($this->tugas, $pimpinan);
                } catch (\Exception $e) {
                    \Illuminate\Support\Facades\Log::warning('WhatsApp notification failed', [
                        'error' => $e->getMessage(),
                    ]);
                }
            }

            session()->flash('success', 'Tugas telah selesai. Menunggu ACC dari pimpinan.');
        }
    }

    public function render()
    {
        return view('livewire.pegawai.tugas.show');
    }
}
