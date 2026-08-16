<?php

namespace App\Livewire\Pegawai;

use App\Models\Tugas;
use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        $pegawai = auth()->user()->pegawai;
        $query = $pegawai ? Tugas::forPegawai($pegawai->id_pegawai) : Tugas::query()->whereRaw('1 = 0');

        return view('livewire.pegawai.dashboard', [
            'totalTugas' => (clone $query)->count(),
            'tugasBelumMulai' => (clone $query)->where('status_tugas', 'belum_mulai')->count(),
            'tugasProses' => (clone $query)->where('status_tugas', 'proses')->count(),
            'tugasSelesai' => (clone $query)->where('status_tugas', 'selesai')->count(),
            'tugasAcc' => (clone $query)->where('status_tugas', 'acc')->count(),
            'recentTugas' => (clone $query)->latest()->take(5)->get(),
        ]);
    }
}
