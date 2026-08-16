<?php
namespace App\Livewire\Pegawai\Tugas;

use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';

    public function render()
    {
        $pegawai = auth()->user()->pegawai;

        return view('livewire.pegawai.tugas.index', [
            'tugases' => $pegawai
                ? $pegawai->tugases()
                    ->when($this->search, fn ($q) => $q->where('nama_tugas', 'like', "%{$this->search}%"))
                    ->latest()
                    ->paginate(10)
                : collect(),
        ]);
    }
}
