<?php

namespace App\Livewire\Pimpinan\Pegawai;

use App\Models\Pegawai;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';

    public function render()
    {
        return view('livewire.pimpinan.pegawai.index', [
            'pegawais' => Pegawai::with(['unit', 'jabatan'])
                ->when($this->search, fn ($q) => $q->where('nama_pegawai', 'like', "%{$this->search}%"))
                ->orderBy('nama_pegawai')
                ->paginate(10),
        ]);
    }
}
