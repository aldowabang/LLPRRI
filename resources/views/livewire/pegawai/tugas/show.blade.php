<div>
    @if (session('success'))
        <div class="mb-4 rounded-lg bg-green-50 p-4 text-green-800 dark:bg-green-900/30 dark:text-green-300">
            {{ session('success') }}
        </div>
    @endif

    <div class="flex items-center justify-between">
        <flux:heading size="xl">{{ $tugas->nama_tugas }}</flux:heading>
        <div class="flex gap-2">
            @if ($tugas->status_tugas === 'belum_mulai')
                <flux:button wire:click="mulai" variant="primary" color="yellow">Mulai Tugas</flux:button>
            @endif
            @if ($tugas->status_tugas === 'proses')
                <flux:button wire:click="selesai" variant="primary" color="green">Tandai Selesai</flux:button>
            @endif
            <flux:button href="{{ route('pegawai.tugases.index') }}" variant="subtle">Kembali</flux:button>
        </div>
    </div>

    <div class="mt-6 max-w-xl">
        <div class="rounded-xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">
            <h3 class="text-lg font-semibold">Detail Tugas</h3>
            <dl class="mt-4 space-y-3">
                <div>
                    <dt class="text-sm text-gray-500">Format</dt>
                    <dd class="text-sm">{{ $tugas->format }}</dd>
                </div>
                <div>
                    <dt class="text-sm text-gray-500">Tanggal Produksi</dt>
                    <dd class="text-sm">{{ $tugas->tanggal_produksi->format('d/m/Y') }}</dd>
                </div>
                <div>
                    <dt class="text-sm text-gray-500">Tanggal Rapat</dt>
                    <dd class="text-sm">{{ $tugas->tanggal_rapat->format('d/m/Y') }}</dd>
                </div>
                <div>
                    <dt class="text-sm text-gray-500">Tempat</dt>
                    <dd class="text-sm">{{ $tugas->tempat }}</dd>
                </div>
                <div>
                    <dt class="text-sm text-gray-500">Topik</dt>
                    <dd class="text-sm">{{ $tugas->topik }}</dd>
                </div>
                <div>
                    <dt class="text-sm text-gray-500">Status</dt>
                    <dd>
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
                    </dd>
                </div>
            </dl>
        </div>
    </div>
</div>
