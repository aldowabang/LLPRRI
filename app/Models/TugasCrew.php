<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TugasCrew extends Model
{
    protected $table = 'tugas_crews';

    protected $fillable = [
        'id_tugas',
        'id_pegawai',
        'peran',
    ];

    public function tugas(): BelongsTo
    {
        return $this->belongsTo(Tugas::class, 'id_tugas', 'id_tugas');
    }

    public function pegawai(): BelongsTo
    {
        return $this->belongsTo(Pegawai::class, 'id_pegawai', 'id_pegawai');
    }

    public function getPeranLabelAttribute(): string
    {
        return match ($this->peran) {
            'produser' => 'Produser',
            'asisten_produser' => 'Asisten Produser',
            'pengarah_acara' => 'Pengarah Acara',
            'asisten_pa' => 'Asisten PA',
            'presenter' => 'Presenter',
            'cameraman' => 'Cameraman',
            'teknisi' => 'Teknisi',
            'editor' => 'Editor',
            'dokumentasi' => 'Dokumentasi/Publikasi',
            'unit_manager' => 'Unit Manager',
            default => ucfirst(str_replace('_', ' ', $this->peran)),
        };
    }
}
