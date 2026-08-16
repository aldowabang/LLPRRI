<div>
    <div class="flex items-center justify-between">
        <flux:heading size="xl">Data Pegawai</flux:heading>
        <flux:button wire:click="openCreate" variant="primary">Tambah Pegawai</flux:button>
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
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">NIP</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Nama</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Unit</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Jabatan</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">No HP</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
                @forelse ($pegawais as $pegawai)
                    <tr>
                        <td class="px-6 py-4 text-sm">{{ $pegawai->nip }}</td>
                        <td class="px-6 py-4 text-sm font-medium">{{ $pegawai->nama_pegawai }}</td>
                        <td class="px-6 py-4 text-sm">{{ $pegawai->unit->nama_unit ?? '-' }}</td>
                        <td class="px-6 py-4 text-sm">{{ $pegawai->jabatan->nama_jabatan ?? '-' }}</td>
                        <td class="px-6 py-4 text-sm">{{ $pegawai->no_hp }}</td>
                        <td class="px-6 py-4 text-right text-sm">
                            <flux:button wire:click="openEdit({{ $pegawai->id_pegawai }})" size="sm" variant="subtle">Edit</flux:button>
                            <flux:button wire:click="delete({{ $pegawai->id_pegawai }})" wire:confirm="Hapus pegawai ini?" size="sm" variant="danger">Hapus</flux:button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-sm text-gray-500">Belum ada data pegawai.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $pegawais->links() }}</div>

    <flux:modal name="pegawai-form" wire:close="close" class="max-w-lg">
        <flux:heading size="lg">{{ $editMode ? 'Edit Pegawai' : 'Tambah Pegawai' }}</flux:heading>
        <div class="mt-4 space-y-4">
            <div class="grid grid-cols-2 gap-4">
                <flux:select wire:model="id_unit" label="Unit">
                    <option value="">Pilih Unit</option>
                    @foreach ($units as $unit)
                        <option value="{{ $unit->id_unit }}">{{ $unit->nama_unit }}</option>
                    @endforeach
                </flux:select>
                <flux:select wire:model="id_jabatan" label="Jabatan">
                    <option value="">Pilih Jabatan</option>
                    @foreach ($jabatans as $jabatan)
                        <option value="{{ $jabatan->id_jabatan }}">{{ $jabatan->nama_jabatan }}</option>
                    @endforeach
                </flux:select>
            </div>
            <flux:input wire:model="nip" label="NIP" />
            <flux:input wire:model="nama_pegawai" label="Nama Pegawai" />
            <flux:select wire:model="jenis_kelamin" label="Jenis Kelamin">
                <option value="L">Laki-laki</option>
                <option value="P">Perempuan</option>
            </flux:select>
            <flux:input wire:model="no_hp" label="No. HP" />
            <flux:textarea wire:model="alamat" label="Alamat" />
            <div class="flex justify-end gap-2">
                <flux:button wire:click="close" variant="subtle">Batal</flux:button>
                <flux:button wire:click="save" variant="primary">Simpan</flux:button>
            </div>
        </div>
    </flux:modal>
</div>
