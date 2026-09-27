<div>
    <flux:heading size="xl">Dashboard Pimpinan</flux:heading>
    <p class="mt-2 text-gray-600 dark:text-gray-400">Selamat datang, {{ auth()->user()->name }}</p>

    <div class="mt-8 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-5">
        <div class="rounded-xl border border-zinc-200 bg-white p-4 dark:border-zinc-700 dark:bg-zinc-900">
            <div class="text-sm text-gray-500">Total Pegawai</div>
            <div class="mt-1 text-2xl font-bold text-zinc-900 dark:text-white">{{ $totalPegawai }}</div>
        </div>
        <div class="rounded-xl border border-zinc-200 bg-white p-4 dark:border-zinc-700 dark:bg-zinc-900">
            <div class="text-sm text-gray-500">Total Tugas</div>
            <div class="mt-1 text-2xl font-bold text-zinc-900 dark:text-white">{{ $totalTugas }}</div>
        </div>
        <div class="rounded-xl border border-yellow-200 bg-yellow-50 p-4 dark:border-yellow-700 dark:bg-yellow-900/20">
            <div class="text-sm text-yellow-700 dark:text-yellow-400">Belum Mulai</div>
            <div class="mt-1 text-2xl font-bold text-yellow-800 dark:text-yellow-300">{{ $tugasBelumMulai }}</div>
        </div>
        <div class="rounded-xl border border-blue-200 bg-blue-50 p-4 dark:border-blue-700 dark:bg-blue-900/20">
            <div class="text-sm text-blue-700 dark:text-blue-400">Proses</div>
            <div class="mt-1 text-2xl font-bold text-blue-800 dark:text-blue-300">{{ $tugasProses }}</div>
        </div>
        <div class="rounded-xl border border-green-200 bg-green-50 p-4 dark:border-green-700 dark:bg-green-900/20">
            <div class="text-sm text-green-700 dark:text-green-400">Selesai / ACC</div>
            <div class="mt-1 text-2xl font-bold text-green-800 dark:text-green-300">{{ $tugasSelesai + $tugasAcc }}</div>
        </div>
    </div>

    <div class="mt-8">
        <div class="flex items-center justify-between">
            <flux:heading size="lg">Tugas Terbaru</flux:heading>
            <div class="flex gap-2">
                <flux:button href="{{ route('manual-book.pdf') }}" variant="subtle" icon="document-arrow-down">Manual Book (PDF)</flux:button>
                <flux:button href="{{ route('pimpinan.tugases.create') }}" variant="primary">Buat Tugas</flux:button>
            </div>
        </div>
        <div class="mt-4 overflow-hidden rounded-xl border border-zinc-200 dark:border-zinc-700">
            <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
                <thead class="bg-zinc-50 dark:bg-zinc-800">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Nama Tugas</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Pegawai</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Tanggal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
                    @forelse ($recentTugas as $tugas)
                        <tr>
                            <td class="px-6 py-4 text-sm font-medium">
                                <a href="{{ route('pimpinan.tugases.show', $tugas->id_tugas) }}" class="text-blue-600 hover:underline">{{ $tugas->nama_tugas }}</a>
                            </td>
                            <td class="px-6 py-4 text-sm">{{ $tugas->pegawai->nama_pegawai ?? '-' }}</td>
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
                            <td class="px-6 py-4 text-sm text-gray-500">{{ $tugas->tanggal_produksi->format('d/m/Y') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-sm text-gray-500">Belum ada tugas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
