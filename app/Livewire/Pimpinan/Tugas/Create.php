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

    public string $format = 'Talkshow';

    public string $disiarkan = '';

    public string $tanggal_rapat = '';

    public string $waktu_rapat = '10:00';

    public string $tempat_rapat = 'Ruang Siaran';

    public string $tanggal_produksi = '';

    public string $waktu_produksi = '13:00';

    public string $tempat_produksi = 'Studio 1';

    public string $narasumber = '';

    public string $topik = '';

    public string $penanggung_jawab = 'Kepala LPP RRI Kupang';

    public string $supervisor = 'Kabag TU dan Para Ketua Tim';

    public $id_pegawai = null; // optional primary lead

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
        $this->disiarkan = date('F Y');
    }

    protected function rules(): array
    {
        return [
            'nomor_nota' => 'nullable|string|max:100',
            'nama_tugas' => 'required|string|max:150',
            'format' => 'required|string|max:50',
            'disiarkan' => 'nullable|string|max:100',
            'tanggal_rapat' => 'required|date',
            'waktu_rapat' => 'nullable|string',
            'tempat_rapat' => 'nullable|string|max:150',
            'tanggal_produksi' => 'required|date',
            'waktu_produksi' => 'nullable|string',
            'tempat_produksi' => 'nullable|string|max:150',
            'narasumber' => 'nullable|string|max:255',
            'topik' => 'required|string',
            'penanggung_jawab' => 'nullable|string|max:150',
            'supervisor' => 'nullable|string|max:150',
            'id_pegawai' => 'nullable|exists:pegawais,id_pegawai',
        ];
    }

    public function render()
    {
        return view('livewire.pimpinan.tugas.create', [
            'pegawais' => Pegawai::orderBy('nama_pegawai')->get(),
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
            'penanggung_jawab' => $this->penanggung_jawab ?: 'Kepala LPP RRI Kupang',
            'supervisor' => $this->supervisor ?: 'Kabag TU dan Para Ketua Tim',
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
