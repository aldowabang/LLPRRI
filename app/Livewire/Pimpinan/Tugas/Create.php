<?php
namespace App\Livewire\Pimpinan\Tugas;

use App\Models\Pegawai;
use App\Models\Tugas;
use App\Services\WhatsAppService;
use Livewire\Component;

class Create extends Component
{
    public $id_pegawai;
    public $nama_tugas = '';
    public $format = '';
    public $tanggal_produksi = '';
    public $tanggal_rapat = '';
    public $tempat = '';
    public $topik = '';

    protected $rules = [
        'id_pegawai' => 'required|exists:pegawais,id_pegawai',
        'nama_tugas' => 'required|string|max:150',
        'format' => 'required|string|max:50',
        'tanggal_produksi' => 'required|date',
        'tanggal_rapat' => 'required|date',
        'tempat' => 'required|string|max:150',
        'topik' => 'required|string',
    ];

    public function render()
    {
        return view('livewire.pimpinan.tugas.create', [
            'pegawais' => Pegawai::orderBy('nama_pegawai')->get(),
        ]);
    }

    public function save()
    {
        $this->validate();

        $tugas = Tugas::create([
            'id_pegawai' => $this->id_pegawai,
            'nama_tugas' => $this->nama_tugas,
            'format' => $this->format,
            'tanggal_produksi' => $this->tanggal_produksi,
            'tanggal_rapat' => $this->tanggal_rapat,
            'tempat' => $this->tempat,
            'topik' => $this->topik,
            'status_tugas' => 'belum_mulai',
        ]);

        $tugas->load('pegawai');

        try {
            app(WhatsAppService::class)->sendTugasBaru($tugas, $tugas->pegawai);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::warning('WhatsApp notification failed', [
                'error' => $e->getMessage(),
            ]);
        }

        session()->flash('success', 'Tugas berhasil dibuat.');
        return $this->redirect(route('pimpinan.tugases.index'));
    }
}
