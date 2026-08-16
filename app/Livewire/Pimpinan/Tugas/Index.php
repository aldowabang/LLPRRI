<?php
namespace App\Livewire\Pimpinan\Tugas;

use App\Models\Tugas;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $status = '';

    public function render()
    {
        return view('livewire.pimpinan.tugas.index', [
            'tugases' => Tugas::with('pegawai')
                ->when($this->search, fn ($q) => $q->where('nama_tugas', 'like', "%{$this->search}%"))
                ->when($this->status, fn ($q) => $q->where('status_tugas', $this->status))
                ->latest()
                ->paginate(10),
        ]);
    }
}
