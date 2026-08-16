<?php
namespace App\Livewire\Admin\Pegawai;

use App\Models\Jabatan;
use App\Models\Pegawai;
use App\Models\Unit;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $pegawaiId;
    public $id_unit;
    public $id_jabatan;
    public $nip = '';
    public $nama_pegawai = '';
    public $jenis_kelamin = 'L';
    public $no_hp = '';
    public $alamat = '';
    public $showModal = false;
    public $editMode = false;

    protected function rules(): array
    {
        return [
            'id_unit' => 'required|exists:units,id_unit',
            'id_jabatan' => 'required|exists:jabatans,id_jabatan',
            'nip' => 'required|string|max:30' . ($this->pegawaiId ? '|unique:pegawais,nip,' . $this->pegawaiId . ',id_pegawai' : '|unique:pegawais,nip'),
            'nama_pegawai' => 'required|string|max:100',
            'jenis_kelamin' => 'required|in:L,P',
            'no_hp' => 'required|string|max:20',
            'alamat' => 'required|string',
        ];
    }

    public function render()
    {
        return view('livewire.admin.pegawai.index', [
            'pegawais' => Pegawai::with(['unit', 'jabatan'])->orderBy('id_pegawai')->paginate(10),
            'units' => Unit::orderBy('nama_unit')->get(),
            'jabatans' => Jabatan::orderBy('nama_jabatan')->get(),
        ]);
    }

    public function openCreate()
    {
        $this->reset(['pegawaiId', 'id_unit', 'id_jabatan', 'nip', 'nama_pegawai', 'jenis_kelamin', 'no_hp', 'alamat']);
        $this->editMode = false;
        $this->showModal = true;
        $this->dispatch('modal-show', name: 'pegawai-form');
    }

    public function openEdit($pegawaiId)
    {
        $pegawai = Pegawai::findOrFail($pegawaiId);
        $this->pegawaiId = $pegawai->id_pegawai;
        $this->id_unit = $pegawai->id_unit;
        $this->id_jabatan = $pegawai->id_jabatan;
        $this->nip = $pegawai->nip;
        $this->nama_pegawai = $pegawai->nama_pegawai;
        $this->jenis_kelamin = $pegawai->jenis_kelamin;
        $this->no_hp = $pegawai->no_hp;
        $this->alamat = $pegawai->alamat;
        $this->editMode = true;
        $this->showModal = true;
        $this->dispatch('modal-show', name: 'pegawai-form');
    }

    public function save()
    {
        $this->validate();

        $data = [
            'id_unit' => $this->id_unit,
            'id_jabatan' => $this->id_jabatan,
            'nip' => $this->nip,
            'nama_pegawai' => $this->nama_pegawai,
            'jenis_kelamin' => $this->jenis_kelamin,
            'no_hp' => $this->no_hp,
            'alamat' => $this->alamat,
        ];

        if ($this->editMode) {
            Pegawai::find($this->pegawaiId)->update($data);
            session()->flash('success', 'Pegawai berhasil diupdate.');
        } else {
            Pegawai::create($data);
            session()->flash('success', 'Pegawai berhasil ditambahkan.');
        }

        $this->showModal = false;
        $this->dispatch('modal-close', name: 'pegawai-form');
    }

    public function close()
    {
        $this->showModal = false;
        $this->dispatch('modal-close', name: 'pegawai-form');
    }

    public function delete($pegawaiId)
    {
        Pegawai::findOrFail($pegawaiId)->delete();
        session()->flash('success', 'Pegawai berhasil dihapus.');
    }
}
