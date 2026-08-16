<div>
    <div class="flex items-center justify-between">
        <flux:heading size="xl">Manajemen Tugas</flux:heading>
        <div class="flex gap-2">
            <flux:button href="{{ route('pimpinan.laporan.pdf') }}" variant="subtle" icon="document-arrow-down">Unduh PDF</flux:button>
            <flux:button href="{{ route('pimpinan.tugases.create') }}" variant="primary">Buat Tugas</flux:button>
        </div>
    </div>

    <div class="mt-4 flex gap-4">
        <flux:input wire:model.live.debounce.300ms="search" placeholder="Cari tugas..." icon="magnifying-glass" class="flex-1" />
        <flux:select wire:model.live="status" class="w-48">
            <option value="">Semua Status</option>
            <option value="belum_mulai">Belum Mulai</option>
            <option value="proses">Proses</option>
            <option value="selesai">Selesai</option>
            <option value="acc">ACC</option>
        </flux:select>
    </div>

    <div class="mt-6 overflow-hidden rounded-xl border border-zinc-200 dark:border-zinc-700">
        <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
            <thead class="bg-zinc-50 dark:bg-zinc-800">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Nama Tugas</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Pegawai</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Format</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Tanggal Produksi</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Status</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
                @forelse ($tugases as $tugas)
                    <tr>
                        <td class="px-6 py-4 text-sm font-medium">{{ $tugas->nama_tugas }}</td>
                        <td class="px-6 py-4 text-sm">{{ $tugas->pegawai->nama_pegawai ?? '-' }}</td>
                        <td class="px-6 py-4 text-sm">{{ $tugas->format }}</td>
                        <td class="px-6 py-4 text-sm">{{ $tugas->tanggal_produksi->format('d/m/Y') }}</td>
                        <td class="px-6 py-4 text-sm">
                            <span class="inline-flex items-center rounded-full px-2 py-1 text-xs font-medium
                                {{ match($tugas->status_tugas) {
                                    'belum_mulai' => 'bg-gray-100 text-gray-800',
                                    'proses' => 'bg-yellow-100 text-yellow-800',
                                    'selesai' => 'bg-blue-100 text-blue-800',
                                    'acc' => 'bg-green-100 text-green-800',
                                    default => 'bg-gray-100 text-gray-800',
                                } }}">
                                {{ $tugas->status_label }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right text-sm">
                            <flux:button href="{{ route('pimpinan.tugases.show', $tugas->id_tugas) }}" size="sm" variant="subtle">Detail</flux:button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-sm text-gray-500">Belum ada tugas.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $tugases->links() }}</div>
</div>
