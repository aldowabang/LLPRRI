<?php

namespace App\Livewire\Pimpinan\Tugas;

use App\Models\Tugas;
use App\Models\TugasCrew;
use Livewire\Component;

class Show extends Component
{
    public Tugas $tugas;

    public function mount($id_tugas): void
    {
        $this->tugas = Tugas::with(['pegawai', 'crews.pegawai'])->findOrFail($id_tugas);
    }

    public function accPerCrew(int $crewId): void
    {
        $crew = TugasCrew::where('id_tugas', $this->tugas->id_tugas)
            ->where('id', $crewId)
            ->first();

        if ($crew && $crew->status === 'selesai') {
            $crew->update(['status' => 'acc']);

            $allAcc = TugasCrew::where('id_tugas', $this->tugas->id_tugas)
                ->where('status', '!=', 'acc')
                ->doesntExist();

            if ($allAcc && $this->tugas->status_tugas !== 'acc') {
                $this->tugas->update(['status_tugas' => 'acc']);
            }

            $this->tugas->refresh();
            session()->flash('success', "Berhasil ACC {$crew->pegawai->nama_pegawai} ({$crew->peran_label}).");
        }
    }

    public function tandaiSemuaSelesai(): void
    {
        TugasCrew::where('id_tugas', $this->tugas->id_tugas)
            ->whereIn('status', ['belum_mulai', 'proses'])
            ->update(['status' => 'selesai']);

        $this->tugas->update(['status_tugas' => 'selesai']);
        $this->tugas->refresh();
        session()->flash('success', 'Semua kerabat kerja ditandai selesai.');
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
