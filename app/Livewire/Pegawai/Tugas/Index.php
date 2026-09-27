<?php

namespace App\Livewire\Pegawai\Tugas;

use App\Models\Tugas;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public string $search = '';

    public function render()
    {
        $pegawai = auth()->user()->pegawai;

        $tugases = $pegawai
            ? Tugas::forPegawai($pegawai->id_pegawai)
                ->when($this->search, fn ($q) => $q->where('nama_tugas', 'like', "%{$this->search}%"))
                ->latest()
                ->paginate(10)
            : collect();

        return view('livewire.pegawai.tugas.index', [
            'tugases' => $tugases,
        ]);
    }
}
