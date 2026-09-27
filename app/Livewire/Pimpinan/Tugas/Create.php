<?php

namespace App\Livewire\Pimpinan\Tugas;

use App\Models\Pegawai;
use App\Models\Tugas;
use App\Models\TugasCrew;
use App\Services\WhatsAppService;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class Create extends Component
{
    public string $nomor_nota = '';

    public string $nama_tugas = '';

    public string $format = '';

    public string $disiarkan = '';

    public string $tanggal_rapat = '';

    public string $waktu_rapat = '';

    public string $tempat_rapat = '';

    public string $tanggal_produksi = '';

    public string $waktu_produksi = '';

    public string $tempat_produksi = '';

    public string $narasumber = '';

    public string $topik = '';

    public string $penanggung_jawab = '';

    public string $supervisor = '';

    public $id_pegawai = null;

    // Roles selection
    public array $crew_roles = [
        'produser' => [],
        'asisten_produser' => [],
        'pengarah_acara' => [],
        'asisten_pa' => [],
        'presenter' => [],
        'cameraman' => [],
        'teknisi' => [],
        'editor' => [],
        'dokumentasi' => [],
        'unit_manager' => [],
    ];

    public function mount(): void
    {
        $year = date('Y');
        $month = date('m');
        $randomNum = rand(1000, 9999);
        $this->nomor_nota = "{$randomNum}/RRI.KPG/XVII.PPS.01.02/{$month}/{$year}";
    }

    protected function rules(): array
    {
        return [
            'nomor_nota' => 'required|string|max:100',
            'nama_tugas' => 'required|string|max:150',
            'format' => 'required|string|max:50',
            'disiarkan' => 'required|string|max:100',
            'tanggal_rapat' => 'required|date',
            'waktu_rapat' => 'required|string',
            'tempat_rapat' => 'required|string|max:150',
            'tanggal_produksi' => 'required|date',
            'waktu_produksi' => 'required|string',
            'tempat_produksi' => 'required|string|max:150',
            'narasumber' => 'required|string|max:255',
            'topik' => 'required|string',
            'penanggung_jawab' => 'required|string|max:150',
            'supervisor' => 'required|string|max:150',
            'id_pegawai' => 'nullable|exists:pegawais,id_pegawai',
            'crew_roles.produser' => 'required|array|min:1',
            'crew_roles.produser.*' => 'exists:pegawais,id_pegawai',
            'crew_roles.cameraman' => 'required|array|min:1',
            'crew_roles.cameraman.*' => 'exists:pegawais,id_pegawai',
        ];
    }

    protected function messages(): array
    {
        return [
            'nomor_nota.required' => 'Nomor nota produksi wajib diisi.',
            'nomor_nota.max' => 'Nomor nota produksi maksimal 100 karakter.',
            'nama_tugas.required' => 'Nama acara / tugas wajib diisi.',
            'nama_tugas.max' => 'Nama acara maksimal 150 karakter.',
            'format.required' => 'Format acara wajib dipilih.',
            'format.max' => 'Format acara maksimal 50 karakter.',
            'disiarkan.required' => 'Jadwal penyiaran wajib diisi.',
            'disiarkan.max' => 'Jadwal penyiaran maksimal 100 karakter.',
            'tanggal_rapat.required' => 'Tanggal rapat pra-produksi wajib diisi.',
            'tanggal_rapat.date' => 'Format tanggal rapat tidak valid.',
            'waktu_rapat.required' => 'Waktu rapat wajib diisi.',
            'tempat_rapat.required' => 'Tempat rapat wajib diisi.',
            'tempat_rapat.max' => 'Tempat rapat maksimal 150 karakter.',
            'tanggal_produksi.required' => 'Tanggal produksi wajib diisi.',
            'tanggal_produksi.date' => 'Format tanggal produksi tidak valid.',
            'waktu_produksi.required' => 'Waktu produksi wajib diisi.',
            'tempat_produksi.required' => 'Tempat produksi wajib diisi.',
            'tempat_produksi.max' => 'Tempat produksi maksimal 150 karakter.',
            'narasumber.required' => 'Narasumber wajib diisi.',
            'narasumber.max' => 'Narasumber maksimal 255 karakter.',
            'topik.required' => 'Topik / judul pembahasan wajib diisi.',
            'penanggung_jawab.required' => 'Penanggung jawab wajib diisi.',
            'penanggung_jawab.max' => 'Penanggung jawab maksimal 150 karakter.',
            'supervisor.required' => 'Supervisor wajib diisi.',
            'supervisor.max' => 'Supervisor maksimal 150 karakter.',
            'crew_roles.produser.required' => 'Produser wajib dipilih minimal 1 orang.',
            'crew_roles.produser.min' => 'Produser wajib dipilih minimal 1 orang.',
            'crew_roles.produser.*.exists' => 'Data pegawai produser tidak valid.',
            'crew_roles.cameraman.required' => 'Cameraman wajib dipilih minimal 1 orang.',
            'crew_roles.cameraman.min' => 'Cameraman wajib dipilih minimal 1 orang.',
            'crew_roles.cameraman.*.exists' => 'Data pegawai cameraman tidak valid.',
        ];
    }

    public function render()
    {
        $semuaPegawai = Pegawai::with('jabatan')->orderBy('nama_pegawai')->get();

        return view('livewire.pimpinan.tugas.create', [
            'pegawais' => $semuaPegawai,
            'produsers' => $semuaPegawai->filter(fn ($p) => $p->jabatan?->nama_jabatan === 'Produser'),
            'cameramans' => $semuaPegawai->filter(fn ($p) => $p->jabatan?->nama_jabatan === 'Cameraman'),
            'teknisis' => $semuaPegawai->filter(fn ($p) => $p->jabatan?->nama_jabatan === 'Teknisi'),
            'editors' => $semuaPegawai->filter(fn ($p) => $p->jabatan?->nama_jabatan === 'Editor'),
            'penyiar' => $semuaPegawai->filter(fn ($p) => $p->jabatan?->nama_jabatan === 'Penyiar'),
            'kepala' => $semuaPegawai->filter(fn ($p) => in_array($p->jabatan?->nama_jabatan, ['Kepala Bidang', 'Kepala Seksi'])),
            'staf' => $semuaPegawai->filter(fn ($p) => $p->jabatan?->nama_jabatan === 'Staf'),
        ]);
    }

    public function save()
    {
        $this->validate();

        // Main primary pegawai (fallbacks to produser if null)
        $primaryPegawaiId = $this->id_pegawai;
        if (! $primaryPegawaiId && ! empty($this->crew_roles['produser'])) {
            $primaryPegawaiId = $this->crew_roles['produser'][0] ?? null;
        }

        $tugas = Tugas::create([
            'nomor_nota' => $this->nomor_nota,
            'id_pegawai' => $primaryPegawaiId,
            'nama_tugas' => $this->nama_tugas,
            'format' => $this->format,
            'disiarkan' => $this->disiarkan,
            'tanggal_rapat' => $this->tanggal_rapat,
            'waktu_rapat' => $this->waktu_rapat,
            'tempat_rapat' => $this->tempat_rapat,
            'tanggal_produksi' => $this->tanggal_produksi,
            'waktu_produksi' => $this->waktu_produksi,
            'tempat_produksi' => $this->tempat_produksi,
            'tempat' => $this->tempat_produksi ?: ($this->tempat_rapat ?: 'RRI Kupang'),
            'narasumber' => $this->narasumber,
            'topik' => $this->topik,
            'penanggung_jawab' => $this->penanggung_jawab,
            'supervisor' => $this->supervisor,
            'status_tugas' => 'belum_mulai',
        ]);

        // Insert crews
        foreach ($this->crew_roles as $peran => $pegawaiIds) {
            if (is_array($pegawaiIds)) {
                foreach ($pegawaiIds as $pegawaiId) {
                    if ($pegawaiId) {
                        TugasCrew::create([
                            'id_tugas' => $tugas->id_tugas,
                            'id_pegawai' => $pegawaiId,
                            'peran' => $peran,
                        ]);
                    }
                }
            } elseif ($pegawaiIds) {
                TugasCrew::create([
                    'id_tugas' => $tugas->id_tugas,
                    'id_pegawai' => $pegawaiIds,
                    'peran' => $peran,
                ]);
            }
        }

        try {
            app(WhatsAppService::class)->broadcastNotaProduksi($tugas);
        } catch (\Exception $e) {
            Log::warning('WhatsApp notification failed', [
                'error' => $e->getMessage(),
            ]);
        }

        session()->flash('success', 'Nota Produksi berhasil dibuat.');

        return $this->redirect(route('pimpinan.tugases.index'));
    }
}
