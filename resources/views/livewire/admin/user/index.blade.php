<div>
    <div class="flex items-center justify-between">
        <flux:heading size="xl">Manajemen User</flux:heading>
        <flux:button wire:click="openCreate" variant="primary">Tambah User</flux:button>
    </div>

    @if (session('success'))
        <div class="mt-4 rounded-lg bg-green-50 p-4 text-green-800 dark:bg-green-900/30 dark:text-green-300">
            {{ session('success') }}
        </div>
    @endif

    <div class="mt-6 overflow-hidden rounded-xl border border-zinc-200 dark:border-zinc-700">
        <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
            <thead class="bg-zinc-50 dark:bg-zinc-800">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Nama</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Email</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Role</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Pegawai</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
                @forelse ($users as $user)
                    <tr>
                        <td class="px-6 py-4 text-sm font-medium">{{ $user->name }}</td>
                        <td class="px-6 py-4 text-sm">{{ $user->email }}</td>
                        <td class="px-6 py-4 text-sm">
                            <span class="inline-flex items-center rounded-full px-2 py-1 text-xs font-medium
                                {{ $user->role === 'admin' ? 'bg-red-100 text-red-800' : ($user->role === 'pimpinan' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800') }}">
                                {{ ucfirst($user->role) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm">{{ $user->pegawai->nama_pegawai ?? '-' }}</td>
                        <td class="px-6 py-4 text-right text-sm">
                            <flux:button wire:click="openEdit({{ $user->id }})" size="sm" variant="subtle">Edit</flux:button>
                            <flux:button wire:click="delete({{ $user->id }})" wire:confirm="Hapus user ini?" size="sm" variant="danger">Hapus</flux:button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-sm text-gray-500">Belum ada data.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $users->links() }}</div>

    <flux:modal name="user-form" wire:close="close" class="max-w-lg">
        <flux:heading size="lg">{{ $editMode ? 'Edit User' : 'Tambah User' }}</flux:heading>
        <div class="mt-4 space-y-4">
            <flux:input wire:model="name" label="Nama" />
            <flux:input wire:model="email" label="Email" type="email" />
            <flux:input wire:model="password" label="{{ $editMode ? 'Password (kosongkan jika tidak diubah)' : 'Password' }}" type="password" />
            <flux:select wire:model="role" label="Role">
                <option value="admin">Admin</option>
                <option value="pimpinan">Pimpinan</option>
                <option value="pegawai">Pegawai</option>
            </flux:select>
            <flux:select wire:model="pegawai_id" label="Pegawai (opsional)">
                <option value="">Tidak dikaitkan</option>
                @foreach ($pegawais as $pegawai)
                    <option value="{{ $pegawai->id_pegawai }}">{{ $pegawai->nama_pegawai }} - {{ $pegawai->nip }}</option>
                @endforeach
            </flux:select>
            <div class="flex justify-end gap-2">
                <flux:button wire:click="close" variant="subtle">Batal</flux:button>
                <flux:button wire:click="save" variant="primary">Simpan</flux:button>
            </div>
        </div>
    </flux:modal>
</div>
