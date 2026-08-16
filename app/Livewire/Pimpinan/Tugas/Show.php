<?php
namespace App\Livewire\Pimpinan\Tugas;

use App\Models\Tugas;
use Livewire\Component;

class Show extends Component
{
    public Tugas $tugas;

    public function mount($id_tugas)
    {
        $this->tugas = Tugas::with('pegawai')->findOrFail($id_tugas);
    }

    public function acc()
    {
        $this->tugas->update(['status_tugas' => 'acc']);
        $this->tugas->refresh();
        session()->flash('success', 'Tugas berhasil di-ACC.');
    }

    public function render()
    {
        return view('livewire.pimpinan.tugas.show');
    }
}
