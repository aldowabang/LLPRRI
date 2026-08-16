<div>
    <flux:heading size="xl">Dashboard Admin</flux:heading>
    <p class="mt-2 text-gray-600 dark:text-gray-400">Selamat datang, {{ auth()->user()->name }}</p>

    <div class="mt-8 grid grid-cols-1 gap-6 sm:grid-cols-3">
        <div class="rounded-xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">
            <div class="text-sm font-medium text-gray-500">Total Pegawai</div>
            <div class="mt-2 text-3xl font-bold text-zinc-900 dark:text-white">{{ $totalPegawai }}</div>
        </div>
        <div class="rounded-xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">
            <div class="text-sm font-medium text-gray-500">Total Unit</div>
            <div class="mt-2 text-3xl font-bold text-zinc-900 dark:text-white">{{ $totalUnit }}</div>
        </div>
        <div class="rounded-xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">
            <div class="text-sm font-medium text-gray-500">Total Jabatan</div>
            <div class="mt-2 text-3xl font-bold text-zinc-900 dark:text-white">{{ $totalJabatan }}</div>
        </div>
    </div>

    <div class="mt-8 flex gap-4">
        <flux:button href="{{ route('admin.units.index') }}" variant="primary">Kelola Unit</flux:button>
        <flux:button href="{{ route('admin.jabatans.index') }}" variant="primary">Kelola Jabatan</flux:button>
        <flux:button href="{{ route('admin.pegawais.index') }}" variant="primary">Kelola Pegawai</flux:button>
        <flux:button href="{{ route('admin.users.index') }}" variant="primary">Kelola User</flux:button>
    </div>
</div>
