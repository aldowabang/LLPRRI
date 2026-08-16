<?php
namespace App\Livewire\Pegawai;

use App\Models\Tugas;
use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        $pegawai = auth()->user()->pegawai;

        return view('livewire.pegawai.dashboard', [
            'totalTugas' => $pegawai ? $pegawai->tugases()->count() : 0,
            'tugasBelumMulai' => $pegawai ? $pegawai->tugases()->where('status_tugas', 'belum_mulai')->count() : 0,
            'tugasProses' => $pegawai ? $pegawai->tugases()->where('status_tugas', 'proses')->count() : 0,
            'tugasSelesai' => $pegawai ? $pegawai->tugases()->where('status_tugas', 'selesai')->count() : 0,
            'tugasAcc' => $pegawai ? $pegawai->tugases()->where('status_tugas', 'acc')->count() : 0,
            'recentTugas' => $pegawai ? $pegawai->tugases()->latest()->take(5)->get() : collect(),
        ]);
    }
}
