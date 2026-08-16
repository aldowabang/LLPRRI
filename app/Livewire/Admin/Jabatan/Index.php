<?php
namespace App\Livewire\Admin\Jabatan;

use App\Models\Jabatan;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $jabatanId;
    public $nama_jabatan = '';
    public $showModal = false;
    public $editMode = false;

    protected $rules = [
        'nama_jabatan' => 'required|string|max:100',
    ];

    public function render()
    {
        return view('livewire.admin.jabatan.index', [
            'jabatans' => Jabatan::orderBy('id_jabatan')->paginate(10),
        ]);
    }

    public function openCreate()
    {
        $this->reset(['jabatanId', 'nama_jabatan']);
        $this->editMode = false;
        $this->showModal = true;
        $this->dispatch('modal-show', name: 'jabatan-form');
    }

    public function openEdit($jabatanId)
    {
        $jabatan = Jabatan::findOrFail($jabatanId);
        $this->jabatanId = $jabatan->id_jabatan;
        $this->nama_jabatan = $jabatan->nama_jabatan;
        $this->editMode = true;
        $this->showModal = true;
        $this->dispatch('modal-show', name: 'jabatan-form');
    }

    public function save()
    {
        $this->validate();

        if ($this->editMode) {
            Jabatan::find($this->jabatanId)->update(['nama_jabatan' => $this->nama_jabatan]);
            session()->flash('success', 'Jabatan berhasil diupdate.');
        } else {
            Jabatan::create(['nama_jabatan' => $this->nama_jabatan]);
            session()->flash('success', 'Jabatan berhasil ditambahkan.');
        }

        $this->showModal = false;
        $this->dispatch('modal-close', name: 'jabatan-form');
    }

    public function close()
    {
        $this->showModal = false;
        $this->dispatch('modal-close', name: 'jabatan-form');
    }

    public function delete($jabatanId)
    {
        Jabatan::findOrFail($jabatanId)->delete();
        session()->flash('success', 'Jabatan berhasil dihapus.');
    }
}
