<?php
namespace App\Livewire\Admin\Unit;

use App\Models\Unit;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $unitId;
    public $nama_unit = '';
    public $showModal = false;
    public $editMode = false;

    protected $rules = [
        'nama_unit' => 'required|string|max:100',
    ];

    public function render()
    {
        return view('livewire.admin.unit.index', [
            'units' => Unit::orderBy('id_unit')->paginate(10),
        ]);
    }

    public function openCreate()
    {
        $this->reset(['unitId', 'nama_unit']);
        $this->editMode = false;
        $this->showModal = true;
        $this->dispatch('modal-show', name: 'unit-form');
    }

    public function openEdit($unitId)
    {
        $unit = Unit::findOrFail($unitId);
        $this->unitId = $unit->id_unit;
        $this->nama_unit = $unit->nama_unit;
        $this->editMode = true;
        $this->showModal = true;
        $this->dispatch('modal-show', name: 'unit-form');
    }

    public function save()
    {
        $this->validate();

        if ($this->editMode) {
            Unit::find($this->unitId)->update(['nama_unit' => $this->nama_unit]);
            session()->flash('success', 'Unit berhasil diupdate.');
        } else {
            Unit::create(['nama_unit' => $this->nama_unit]);
            session()->flash('success', 'Unit berhasil ditambahkan.');
        }

        $this->showModal = false;
        $this->dispatch('modal-close', name: 'unit-form');
    }

    public function close()
    {
        $this->showModal = false;
        $this->dispatch('modal-close', name: 'unit-form');
    }

    public function delete($unitId)
    {
        Unit::findOrFail($unitId)->delete();
        session()->flash('success', 'Unit berhasil dihapus.');
    }
}
