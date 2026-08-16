<?php

namespace App\Livewire\Admin\User;

use App\Models\Pegawai;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $userId;

    public $name = '';

    public $email = '';

    public $password = '';

    public $role = 'pegawai';

    public $pegawai_id;

    public $showModal = false;

    public $editMode = false;

    public array $pegawais = [];

    protected function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255'.($this->userId ? '|unique:users,email,'.$this->userId : '|unique:users,email'),
            'password' => $this->editMode ? 'nullable|string|min:8' : 'required|string|min:8',
            'role' => 'required|in:admin,pimpinan,pegawai',
            'pegawai_id' => 'nullable|exists:pegawais,id_pegawai',
        ];
    }

    public function render()
    {
        return view('livewire.admin.user.index', [
            'users' => User::with('pegawai')->orderBy('id')->paginate(10),
        ]);
    }

    public function openCreate()
    {
        $this->reset(['userId', 'name', 'email', 'password', 'role', 'pegawai_id']);
        $this->editMode = false;
        $this->loadPegawais();
        $this->showModal = true;
        $this->dispatch('modal-show', name: 'user-form');
    }

    public function openEdit($userId)
    {
        $user = User::findOrFail($userId);
        $this->userId = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->password = '';
        $this->role = $user->role;
        $this->pegawai_id = $user->pegawai_id;
        $this->editMode = true;
        $this->loadPegawais();
        $this->showModal = true;
        $this->dispatch('modal-show', name: 'user-form');
    }

    private function loadPegawais(): void
    {
        $usedIds = User::whereNotNull('pegawai_id')
            ->when($this->editMode && $this->userId, function ($q) {
                $q->where('id', '!=', $this->userId);
            })
            ->pluck('pegawai_id')
            ->toArray();

        $this->pegawais = Pegawai::whereNotIn('id_pegawai', $usedIds)
            ->orderBy('nama_pegawai')
            ->get()
            ->toArray();
    }

    public function save()
    {
        $this->validate();

        if ($this->editMode) {
            $user = User::find($this->userId);
            $user->update([
                'name' => $this->name,
                'email' => $this->email,
                'role' => $this->role,
                'pegawai_id' => $this->pegawai_id,
            ]);

            if ($this->password) {
                $user->update(['password' => Hash::make($this->password)]);
            }
            session()->flash('success', 'User berhasil diupdate.');
        } else {
            User::create([
                'name' => $this->name,
                'email' => $this->email,
                'password' => Hash::make($this->password),
                'role' => $this->role,
                'pegawai_id' => $this->pegawai_id,
                'email_verified_at' => now(),
            ]);
            session()->flash('success', 'User berhasil ditambahkan.');
        }

        $this->showModal = false;
        $this->dispatch('modal-close', name: 'user-form');
    }

    public function close()
    {
        $this->showModal = false;
        $this->dispatch('modal-close', name: 'user-form');
    }

    public function delete($userId)
    {
        User::findOrFail($userId)->delete();
        session()->flash('success', 'User berhasil dihapus.');
    }
}
