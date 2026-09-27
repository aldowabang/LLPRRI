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
                <div>
                    <flux:input wire:model="nomor_nota" label="Nomor Nota Produksi *" placeholder="1198/RRI.KPG/XVII.PPS.01.02/07/2025" />
                    @error('nomor_nota') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
                <div>
                    <flux:input wire:model="nama_tugas" label="Nama Acara / Tugas *" placeholder="Contoh: Podcastkoe" />
                    @error('nama_tugas') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <flux:input wire:model="format" label="Format Acara *" placeholder="Contoh: Talkshow, Liputan, Siaran" />
                    @error('format') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
                <div>
                    <flux:input wire:model="disiarkan" label="Jadwal Penyiaran (Disiarkan) *" type="date" />
                    @error('disiarkan') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <flux:input wire:model="tanggal_rapat" label="Tanggal Rapat Pra-Produksi *" type="date" />
                    @error('tanggal_rapat') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
                <div>
                    <flux:input wire:model="waktu_rapat" label="Waktu Rapat *" type="time" />
                    @error('waktu_rapat') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
                <div>
                    <flux:input wire:model="tempat_rapat" label="Tempat Rapat *" placeholder="Ruang Siaran" />
                    @error('tempat_rapat') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <flux:input wire:model="tanggal_produksi" label="Tanggal Produksi *" type="date" />
                    @error('tanggal_produksi') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
                <div>
                    <flux:input wire:model="waktu_produksi" label="Waktu Produksi *" type="time" />
                    @error('waktu_produksi') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
                <div>
                    <flux:input wire:model="tempat_produksi" label="Tempat Produksi *" placeholder="Studio 1" />
                    @error('tempat_produksi') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <flux:input wire:model="narasumber" label="Narasumber *" placeholder="Contoh: Aiptu Imelda Mella" />
                    @error('narasumber') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
                <div>
                    <flux:select wire:model="penanggung_jawab" label="Penanggung Jawab Umum *">
                        <flux:select.option value="">-- Pilih Penanggung Jawab --</flux:select.option>
                        @foreach ($pegawais as $p)
                            <flux:select.option value="{{ $p->nama_pegawai }}">{{ $p->nama_pegawai }} — {{ $p->jabatan?->nama_jabatan ?? '-' }}</flux:select.option>
                        @endforeach
                    </flux:select>
                    @error('penanggung_jawab') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <flux:select wire:model="supervisor" label="Supervisor *">
                    <flux:select.option value="">-- Pilih Supervisor --</flux:select.option>
                    @foreach ($pegawais as $p)
                        <flux:select.option value="{{ $p->nama_pegawai }}">{{ $p->nama_pegawai }} — {{ $p->jabatan?->nama_jabatan ?? '-' }}</flux:select.option>
                    @endforeach
                </flux:select>
                @error('supervisor') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>
            <div>
                <flux:textarea wire:model="topik" label="Topik / Judul Pembahasan *" placeholder="Contoh: Kanker Mengajarkanku Lebih" />
                @error('topik') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>
        </div>

        <!-- Section 2: Tim / Kerabat Kerja -->
        <div class="rounded-xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900 space-y-5">
            <h3 class="text-base font-semibold text-zinc-900 dark:text-zinc-100 border-b pb-2 dark:border-zinc-800">
                2. Penugasan Tim / Kerabat Kerja
            </h3>
            <p class="text-xs text-zinc-500">Pilih pegawai untuk mengisi posisi kerabat kerja produksi.</p>

            <!-- Single-role fields -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <flux:select wire:model="crew_roles.produser.0" label="Produser *">
                        <flux:select.option value="">-- Pilih Produser --</flux:select.option>
                        @foreach ($produsers as $p)
                            <flux:select.option value="{{ $p->id_pegawai }}">{{ $p->nama_pegawai }} ({{ $p->nip }})</flux:select.option>
                        @endforeach
                    </flux:select>
                    @error('crew_roles.produser') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <div>
                    <flux:select wire:model="crew_roles.asisten_produser.0" label="Asisten Produser">
                        <flux:select.option value="">-- Pilih Asisten Produser --</flux:select.option>
                        @foreach ($staf as $p)
                            <flux:select.option value="{{ $p->id_pegawai }}">{{ $p->nama_pegawai }}</flux:select.option>
                        @endforeach
                    </flux:select>
                </div>

                <div>
                    <flux:select wire:model="crew_roles.pengarah_acara.0" label="Pengarah Acara (PA)">
                        <flux:select.option value="">-- Pilih Pengarah Acara --</flux:select.option>
                        @foreach ($penyiar as $p)
                            <flux:select.option value="{{ $p->id_pegawai }}">{{ $p->nama_pegawai }}</flux:select.option>
                        @endforeach
                    </flux:select>
                </div>

                <div>
                    <flux:select wire:model="crew_roles.asisten_pa.0" label="Asisten PA">
                        <flux:select.option value="">-- Pilih Asisten PA --</flux:select.option>
                        @foreach ($staf as $p)
                            <flux:select.option value="{{ $p->id_pegawai }}">{{ $p->nama_pegawai }}</flux:select.option>
                        @endforeach
                    </flux:select>
                </div>

                <div>
                    <flux:select wire:model="crew_roles.presenter.0" label="Presenter">
                        <flux:select.option value="">-- Pilih Presenter --</flux:select.option>
                        @foreach ($penyiar as $p)
                            <flux:select.option value="{{ $p->id_pegawai }}">{{ $p->nama_pegawai }}</flux:select.option>
                        @endforeach
                    </flux:select>
                </div>

                <div>
                    <flux:select wire:model="crew_roles.editor.0" label="Editor">
                        <flux:select.option value="">-- Pilih Editor --</flux:select.option>
                        @foreach ($editors as $p)
                            <flux:select.option value="{{ $p->id_pegawai }}">{{ $p->nama_pegawai }}</flux:select.option>
                        @endforeach
                    </flux:select>
                </div>
            </div>

            <hr class="border-zinc-200 dark:border-zinc-700">

            <!-- Multi-role fields (checkboxes) -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Cameraman -->
                <div>
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">Cameraman * <span class="text-zinc-400 font-normal">(bisa pilih lebih dari 1)</span></label>
                    <div class="space-y-1.5 max-h-40 overflow-y-auto rounded-lg border border-zinc-200 dark:border-zinc-700 p-3 bg-zinc-50 dark:bg-zinc-800/50">
                        @forelse ($cameramans as $p)
                            <label class="flex items-center gap-2 cursor-pointer text-sm text-zinc-700 dark:text-zinc-300 hover:text-zinc-900 dark:hover:text-white">
                                <input type="checkbox" wire:model="crew_roles.cameraman" value="{{ $p->id_pegawai }}" class="size-4 rounded border-zinc-300 dark:border-zinc-600 text-blue-600 focus:ring-blue-500">
                                {{ $p->nama_pegawai }}
                            </label>
                        @empty
                            <p class="text-xs text-zinc-400 italic">Tidak ada pegawai dengan jabatan Cameraman.</p>
                        @endforelse
                    </div>
                    @error('crew_roles.cameraman') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <!-- Teknisi -->
                <div>
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">Teknisi <span class="text-zinc-400 font-normal">(bisa pilih lebih dari 1)</span></label>
                    <div class="space-y-1.5 max-h-40 overflow-y-auto rounded-lg border border-zinc-200 dark:border-zinc-700 p-3 bg-zinc-50 dark:bg-zinc-800/50">
                        @forelse ($teknisis as $p)
                            <label class="flex items-center gap-2 cursor-pointer text-sm text-zinc-700 dark:text-zinc-300 hover:text-zinc-900 dark:hover:text-white">
                                <input type="checkbox" wire:model="crew_roles.teknisi" value="{{ $p->id_pegawai }}" class="size-4 rounded border-zinc-300 dark:border-zinc-600 text-blue-600 focus:ring-blue-500">
                                {{ $p->nama_pegawai }}
                            </label>
                        @empty
                            <p class="text-xs text-zinc-400 italic">Tidak ada pegawai dengan jabatan Teknisi.</p>
                        @endforelse
                    </div>
                </div>

                <!-- Dokumentasi / Publikasi -->
                <div>
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">Dokumentasi / Publikasi <span class="text-zinc-400 font-normal">(bisa pilih lebih dari 1)</span></label>
                    <div class="space-y-1.5 max-h-40 overflow-y-auto rounded-lg border border-zinc-200 dark:border-zinc-700 p-3 bg-zinc-50 dark:bg-zinc-800/50">
                        @forelse ($cameramans->merge($staf) as $p)
                            <label class="flex items-center gap-2 cursor-pointer text-sm text-zinc-700 dark:text-zinc-300 hover:text-zinc-900 dark:hover:text-white">
                                <input type="checkbox" wire:model="crew_roles.dokumentasi" value="{{ $p->id_pegawai }}" class="size-4 rounded border-zinc-300 dark:border-zinc-600 text-blue-600 focus:ring-blue-500">
                                {{ $p->nama_pegawai }}
                            </label>
                        @empty
                            <p class="text-xs text-zinc-400 italic">Tidak ada pegawai tersedia.</p>
                        @endforelse
                    </div>
                </div>

                <!-- Unit Manager -->
                <div>
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">Unit Manager <span class="text-zinc-400 font-normal">(bisa pilih lebih dari 1)</span></label>
                    <div class="space-y-1.5 max-h-40 overflow-y-auto rounded-lg border border-zinc-200 dark:border-zinc-700 p-3 bg-zinc-50 dark:bg-zinc-800/50">
                        @forelse ($kepala as $p)
                            <label class="flex items-center gap-2 cursor-pointer text-sm text-zinc-700 dark:text-zinc-300 hover:text-zinc-900 dark:hover:text-white">
                                <input type="checkbox" wire:model="crew_roles.unit_manager" value="{{ $p->id_pegawai }}" class="size-4 rounded border-zinc-300 dark:border-zinc-600 text-blue-600 focus:ring-blue-500">
                                {{ $p->nama_pegawai }}
                            </label>
                        @empty
                            <p class="text-xs text-zinc-400 italic">Tidak ada pegawai dengan jabatan Kepala.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <div class="flex gap-3 pt-2">
            <flux:button type="submit" variant="primary">Terbitkan Nota Produksi & Kirim WA</flux:button>
            <flux:button href="{{ route('pimpinan.tugases.index') }}" variant="subtle">Batal</flux:button>
        </div>
    </form>
</div>
