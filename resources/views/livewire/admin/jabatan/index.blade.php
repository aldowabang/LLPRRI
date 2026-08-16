<div>
    <div class="flex items-center justify-between">
        <flux:heading size="xl">Jabatan</flux:heading>
        <flux:button wire:click="openCreate" variant="primary">Tambah Jabatan</flux:button>
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
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Nama Jabatan</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
                @forelse ($jabatans as $jabatan)
                    <tr>
                        <td class="px-6 py-4 text-sm">{{ $jabatan->id_jabatan }}</td>
                        <td class="px-6 py-4 text-sm">{{ $jabatan->nama_jabatan }}</td>
                        <td class="px-6 py-4 text-right text-sm">
                            <flux:button wire:click="openEdit({{ $jabatan->id_jabatan }})" size="sm" variant="subtle">Edit</flux:button>
                            <flux:button wire:click="delete({{ $jabatan->id_jabatan }})" wire:confirm="Hapus jabatan ini?" size="sm" variant="danger">Hapus</flux:button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="px-6 py-8 text-center text-sm text-gray-500">Belum ada data jabatan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $jabatans->links() }}</div>

    <flux:modal name="jabatan-form" wire:close="close">
        <flux:heading size="lg">{{ $editMode ? 'Edit Jabatan' : 'Tambah Jabatan' }}</flux:heading>
        <div class="mt-4 space-y-4">
            <flux:input wire:model="nama_jabatan" label="Nama Jabatan" />
            <div class="flex justify-end gap-2">
                <flux:button wire:click="close" variant="subtle">Batal</flux:button>
                <flux:button wire:click="save" variant="primary">Simpan</flux:button>
            </div>
        </div>
    </flux:modal>
</div>
