<?php

namespace App\Livewire\Pimpinan\Tugas;

use App\Models\Tugas;
use Livewire\Component;

class Show extends Component
{
    public Tugas $tugas;

    public function mount($id_tugas): void
    {
        $this->tugas = Tugas::with(['pegawai', 'crews.pegawai'])->findOrFail($id_tugas);
    }

    public function acc(): void
    {
        $this->tugas->update(['status_tugas' => 'acc']);
        $this->tugas->refresh();
        session()->flash('success', 'Nota Produksi / Tugas berhasil di-ACC.');
    }

    public function render()
    {
        return view('livewire.pimpinan.tugas.show');
    }
}
