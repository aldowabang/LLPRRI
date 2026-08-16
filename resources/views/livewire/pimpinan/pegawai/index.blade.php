<div>
    <flux:heading size="xl">Data Pegawai</flux:heading>

    <div class="mt-4">
        <flux:input wire:model.live.debounce.300ms="search" placeholder="Cari nama pegawai..." icon="magnifying-glass" />
    </div>

    <div class="mt-6 overflow-hidden rounded-xl border border-zinc-200 dark:border-zinc-700">
        <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
            <thead class="bg-zinc-50 dark:bg-zinc-800">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">NIP</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Nama</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Unit</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Jabatan</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">No HP</th>
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
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-sm text-gray-500">Tidak ada data pegawai.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $pegawais->links() }}</div>
</div>
