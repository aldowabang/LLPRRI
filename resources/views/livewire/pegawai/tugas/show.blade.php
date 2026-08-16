<div class="space-y-6">
    @if (session('success'))
        <div class="rounded-lg bg-green-50 p-4 text-green-800 dark:bg-green-900/30 dark:text-green-300">
            {{ session('success') }}
        </div>
    @endif

    <div class="flex items-center justify-between">
        <div>
            <flux:heading size="xl">{{ $tugas->nama_tugas }}</flux:heading>
            <flux:subheading>Nota Produksi No: {{ $tugas->nomor_nota ?? '-' }}</flux:subheading>
        </div>
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

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
        <div class="rounded-xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">
            <h3 class="text-base font-semibold text-zinc-900 dark:text-zinc-100 border-b pb-2 dark:border-zinc-800">
                1. Detail Acara & Produksi
            </h3>
            <dl class="mt-4 space-y-3">
                <div class="grid grid-cols-3 gap-2">
                    <dt class="text-sm font-medium text-gray-500">Format</dt>
                    <dd class="col-span-2 text-sm text-zinc-900 dark:text-zinc-100">{{ $tugas->format }}</dd>
                </div>
                <div class="grid grid-cols-3 gap-2">
                    <dt class="text-sm font-medium text-gray-500">Disiarkan</dt>
                    <dd class="col-span-2 text-sm text-zinc-900 dark:text-zinc-100">{{ $tugas->disiarkan ?? '-' }}</dd>
                </div>
                <div class="grid grid-cols-3 gap-2">
                    <dt class="text-sm font-medium text-gray-500">Rapat Pra-Produksi</dt>
                    <dd class="col-span-2 text-sm text-zinc-900 dark:text-zinc-100">
                        {{ $tugas->tanggal_rapat ? $tugas->tanggal_rapat->format('d/m/Y') : '-' }}
                        {{ $tugas->waktu_rapat ? 'jam ' . substr($tugas->waktu_rapat, 0, 5) : '' }}
                        ({{ $tugas->tempat_rapat ?? 'Ruang Siaran' }})
                    </dd>
                </div>
                <div class="grid grid-cols-3 gap-2">
                    <dt class="text-sm font-medium text-gray-500">Jadwal Produksi</dt>
                    <dd class="col-span-2 text-sm text-zinc-900 dark:text-zinc-100">
                        {{ $tugas->tanggal_produksi ? $tugas->tanggal_produksi->format('d/m/Y') : '-' }}
                        {{ $tugas->waktu_produksi ? 'jam ' . substr($tugas->waktu_produksi, 0, 5) : '' }}
                        ({{ $tugas->tempat_produksi ?? $tugas->tempat }})
                    </dd>
                </div>
                <div class="grid grid-cols-3 gap-2">
                    <dt class="text-sm font-medium text-gray-500">Narasumber</dt>
                    <dd class="col-span-2 text-sm text-zinc-900 dark:text-zinc-100">{{ $tugas->narasumber ?? '-' }}</dd>
                </div>
                <div class="grid grid-cols-3 gap-2">
                    <dt class="text-sm font-medium text-gray-500">Topik</dt>
                    <dd class="col-span-2 text-sm text-zinc-900 dark:text-zinc-100">{{ $tugas->topik }}</dd>
                </div>
                <div class="grid grid-cols-3 gap-2">
                    <dt class="text-sm font-medium text-gray-500">Status</dt>
                    <dd class="col-span-2">
                        <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium
                            {{ match($tugas->status_tugas) {
                                'belum_mulai' => 'bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-200',
                                'proses' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/40 dark:text-yellow-300',
                                'selesai' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/40 dark:text-blue-300',
                                'acc' => 'bg-green-100 text-green-800 dark:bg-green-900/40 dark:text-green-300',
                                default => 'bg-gray-100 text-gray-800',
                            } }}">
                            {{ $tugas->status_label }}
                        </span>
                    </dd>
                </div>
            </dl>
        </div>

        <div class="rounded-xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">
            <h3 class="text-base font-semibold text-zinc-900 dark:text-zinc-100 border-b pb-2 dark:border-zinc-800">
                2. Kerabat Kerja Produksi
            </h3>
            <dl class="mt-4 space-y-2">
                <div class="grid grid-cols-3 gap-2 text-sm">
                    <dt class="font-medium text-gray-500">Produser</dt>
                    <dd class="col-span-2 text-zinc-900 dark:text-zinc-100">{{ $tugas->getCrewNamesByRole('produser') }}</dd>
                </div>
                <div class="grid grid-cols-3 gap-2 text-sm">
                    <dt class="font-medium text-gray-500">Pengarah Acara</dt>
                    <dd class="col-span-2 text-zinc-900 dark:text-zinc-100">{{ $tugas->getCrewNamesByRole('pengarah_acara') }}</dd>
                </div>
                <div class="grid grid-cols-3 gap-2 text-sm">
                    <dt class="font-medium text-gray-500">Presenter</dt>
                    <dd class="col-span-2 text-zinc-900 dark:text-zinc-100">{{ $tugas->getCrewNamesByRole('presenter') }}</dd>
                </div>
                <div class="grid grid-cols-3 gap-2 text-sm">
                    <dt class="font-medium text-gray-500">Cameraman</dt>
                    <dd class="col-span-2 text-zinc-900 dark:text-zinc-100">{{ $tugas->getCrewNamesByRole('cameraman') }}</dd>
                </div>
                <div class="grid grid-cols-3 gap-2 text-sm">
                    <dt class="font-medium text-gray-500">Teknisi</dt>
                    <dd class="col-span-2 text-zinc-900 dark:text-zinc-100">{{ $tugas->getCrewNamesByRole('teknisi') }}</dd>
                </div>
                <div class="grid grid-cols-3 gap-2 text-sm">
                    <dt class="font-medium text-gray-500">Editor</dt>
                    <dd class="col-span-2 text-zinc-900 dark:text-zinc-100">{{ $tugas->getCrewNamesByRole('editor') }}</dd>
                </div>
            </dl>
        </div>
    </div>
</div>
