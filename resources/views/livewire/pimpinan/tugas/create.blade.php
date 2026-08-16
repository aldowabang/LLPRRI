<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <flux:heading size="xl">Buat Nota Produksi Baru</flux:heading>
            <flux:subheading>Isi formulir penugasan produksi dan tentukan tim/kerabat kerja.</flux:subheading>
        </div>
        <flux:button href="{{ route('pimpinan.tugases.index') }}" variant="subtle">Kembali</flux:button>
    </div>

    <form wire:submit="save" class="space-y-8">
        <!-- Section 1: Informasi Dokumen & Acara -->
        <div class="rounded-xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900 space-y-4">
            <h3 class="text-base font-semibold text-zinc-900 dark:text-zinc-100 border-b pb-2 dark:border-zinc-800">
                1. Informasi Nota Produksi & Acara
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <flux:input wire:model="nomor_nota" label="Nomor Nota Produksi" placeholder="1198/RRI.KPG/XVII.PPS.01.02/07/2025" />
                <flux:input wire:model="nama_tugas" label="Nama Acara / Tugas" placeholder="Contoh: Podcastkoe" />
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <flux:input wire:model="format" label="Format Acara" placeholder="Contoh: Talkshow, Liputan, Siaran" />
                <flux:input wire:model="disiarkan" label="Jadwal Penyiaran (Disiarkan)" placeholder="Contoh: Juli 2025" />
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <flux:input wire:model="tanggal_rapat" label="Tanggal Rapat Pra-Produksi" type="date" />
                <flux:input wire:model="waktu_rapat" label="Waktu Rapat" type="time" />
                <flux:input wire:model="tempat_rapat" label="Tempat Rapat" placeholder="Ruang Siaran" />
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <flux:input wire:model="tanggal_produksi" label="Tanggal Produksi" type="date" />
                <flux:input wire:model="waktu_produksi" label="Waktu Produksi" type="time" />
                <flux:input wire:model="tempat_produksi" label="Tempat Produksi" placeholder="Studio 1" />
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <flux:input wire:model="narasumber" label="Narasumber" placeholder="Contoh: Aiptu Imelda Mella" />
                <flux:input wire:model="penanggung_jawab" label="Penanggung Jawab Umum" placeholder="Kepala LPP RRI Kupang" />
            </div>

            <flux:input wire:model="supervisor" label="Supervisor" placeholder="Kabag TU dan Para Ketua Tim" />
            <flux:textarea wire:model="topik" label="Topik / Judul Pembahasan" placeholder="Contoh: Kanker Mengajarkanku Lebih" />
        </div>

        <!-- Section 2: Tim / Kerabat Kerja -->
        <div class="rounded-xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900 space-y-4">
            <h3 class="text-base font-semibold text-zinc-900 dark:text-zinc-100 border-b pb-2 dark:border-zinc-800">
                2. Penugasan Tim / Kerabat Kerja
            </h3>
            <p class="text-xs text-zinc-500">Pilih pegawai untuk mengisi posisi kerabat kerja produksi.</p>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Produser -->
                <div>
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Produser</label>
                    <select wire:model="crew_roles.produser.0" class="w-full rounded-lg border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-sm">
                        <option value="">-- Pilih Produser --</option>
                        @foreach ($pegawais as $pegawai)
                            <option value="{{ $pegawai->id_pegawai }}">{{ $pegawai->nama_pegawai }} ({{ $pegawai->nip }})</option>
                        @endforeach
                    </select>
                </div>

                <!-- Asisten Produser -->
                <div>
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Asisten Produser</label>
                    <select wire:model="crew_roles.asisten_produser.0" class="w-full rounded-lg border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-sm">
                        <option value="">-- Pilih Asisten Produser --</option>
                        @foreach ($pegawais as $pegawai)
                            <option value="{{ $pegawai->id_pegawai }}">{{ $pegawai->nama_pegawai }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Pengarah Acara -->
                <div>
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Pengarah Acara (PA)</label>
                    <select wire:model="crew_roles.pengarah_acara.0" class="w-full rounded-lg border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-sm">
                        <option value="">-- Pilih Pengarah Acara --</option>
                        @foreach ($pegawais as $pegawai)
                            <option value="{{ $pegawai->id_pegawai }}">{{ $pegawai->nama_pegawai }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Asisten PA -->
                <div>
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Asisten PA</label>
                    <select wire:model="crew_roles.asisten_pa.0" class="w-full rounded-lg border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-sm">
                        <option value="">-- Pilih Asisten PA --</option>
                        @foreach ($pegawais as $pegawai)
                            <option value="{{ $pegawai->id_pegawai }}">{{ $pegawai->nama_pegawai }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Presenter -->
                <div>
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Presenter</label>
                    <select wire:model="crew_roles.presenter.0" class="w-full rounded-lg border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-sm">
                        <option value="">-- Pilih Presenter --</option>
                        @foreach ($pegawais as $pegawai)
                            <option value="{{ $pegawai->id_pegawai }}">{{ $pegawai->nama_pegawai }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Editor -->
                <div>
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Editor</label>
                    <select wire:model="crew_roles.editor.0" class="w-full rounded-lg border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-sm">
                        <option value="">-- Pilih Editor --</option>
                        @foreach ($pegawais as $pegawai)
                            <option value="{{ $pegawai->id_pegawai }}">{{ $pegawai->nama_pegawai }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Cameraman (Multi select) -->
                <div>
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Cameraman (Bisa Pilih >1)</label>
                    <select wire:model="crew_roles.cameraman" multiple class="w-full rounded-lg border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-sm h-28">
                        @foreach ($pegawais as $pegawai)
                            <option value="{{ $pegawai->id_pegawai }}">{{ $pegawai->nama_pegawai }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Teknisi (Multi select) -->
                <div>
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Teknisi (Bisa Pilih >1)</label>
                    <select wire:model="crew_roles.teknisi" multiple class="w-full rounded-lg border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-sm h-28">
                        @foreach ($pegawais as $pegawai)
                            <option value="{{ $pegawai->id_pegawai }}">{{ $pegawai->nama_pegawai }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Dokumentasi / Publikasi (Multi select) -->
                <div>
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Dokumentasi / Publikasi</label>
                    <select wire:model="crew_roles.dokumentasi" multiple class="w-full rounded-lg border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-sm h-24">
                        @foreach ($pegawais as $pegawai)
                            <option value="{{ $pegawai->id_pegawai }}">{{ $pegawai->nama_pegawai }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Unit Manager (Multi select) -->
                <div>
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Unit Manager</label>
                    <select wire:model="crew_roles.unit_manager" multiple class="w-full rounded-lg border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-sm h-24">
                        @foreach ($pegawais as $pegawai)
                            <option value="{{ $pegawai->id_pegawai }}">{{ $pegawai->nama_pegawai }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="flex gap-3 pt-2">
            <flux:button type="submit" variant="primary">Terbitkan Nota Produksi & Kirim WA</flux:button>
            <flux:button href="{{ route('pimpinan.tugases.index') }}" variant="subtle">Batal</flux:button>
        </div>
    </form>
</div>
