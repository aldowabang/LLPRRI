<?php

namespace App\Livewire\Pimpinan;

use App\Models\Pegawai;
use App\Models\Tugas;
use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        $userId = auth()->id();

        return view('livewire.pimpinan.dashboard', [
            'totalPegawai' => Pegawai::count(),
            'totalTugas' => Tugas::count(),
            'tugasBelumMulai' => Tugas::where('status_tugas', 'belum_mulai')->count(),
            'tugasProses' => Tugas::where('status_tugas', 'proses')->count(),
            'tugasSelesai' => Tugas::where('status_tugas', 'selesai')->count(),
            'tugasAcc' => Tugas::where('status_tugas', 'acc')->count(),
            'recentTugas' => Tugas::with('pegawai')->latest()->take(5)->get(),
        ]);
    }
}
