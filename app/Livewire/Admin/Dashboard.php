<?php
namespace App\Livewire\Admin;

use App\Models\Jabatan;
use App\Models\Pegawai;
use App\Models\Unit;
use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        return view('livewire.admin.dashboard', [
            'totalPegawai' => Pegawai::count(),
            'totalUnit' => Unit::count(),
            'totalJabatan' => Jabatan::count(),
        ]);
    }
}
