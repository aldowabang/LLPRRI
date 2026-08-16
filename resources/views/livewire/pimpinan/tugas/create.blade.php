<div>
    <flux:heading size="xl">Buat Tugas Baru</flux:heading>

    <div class="mt-6 max-w-xl">
        <form wire:submit="save" class="space-y-4">
            <flux:select wire:model="id_pegawai" label="Pegawai yang Ditugaskan">
                <option value="">Pilih Pegawai</option>
                @foreach ($pegawais as $pegawai)
                    <option value="{{ $pegawai->id_pegawai }}">{{ $pegawai->nama_pegawai }} - {{ $pegawai->nip }}</option>
                @endforeach
            </flux:select>

            <flux:input wire:model="nama_tugas" label="Nama Tugas" />
            <flux:input wire:model="format" label="Format" placeholder="Contoh: Liputan, Siaran, dll" />

            <div class="grid grid-cols-2 gap-4">
                <flux:input wire:model="tanggal_produksi" label="Tanggal Produksi" type="date" />
                <flux:input wire:model="tanggal_rapat" label="Tanggal Rapat" type="date" />
            </div>

            <flux:input wire:model="tempat" label="Tempat" />
            <flux:textarea wire:model="topik" label="Topik" />

            <div class="flex gap-2 pt-4">
                <flux:button type="submit" variant="primary">Simpan Tugas</flux:button>
                <flux:button href="{{ route('pimpinan.tugases.index') }}" variant="subtle">Batal</flux:button>
            </div>
        </form>
    </div>
</div>
