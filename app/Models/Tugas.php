<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Tugas extends Model
{
    protected $table = 'tugases';
    protected $primaryKey = 'id_tugas';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'id_pegawai',
        'nama_tugas',
        'format',
        'tanggal_produksi',
        'tanggal_rapat',
        'tempat',
        'topik',
        'status_tugas',
    ];

    protected $casts = [
        'tanggal_produksi' => 'date',
        'tanggal_rapat' => 'date',
    ];

    public function pegawai(): BelongsTo
    {
        return $this->belongsTo(Pegawai::class, 'id_pegawai', 'id_pegawai');
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status_tugas) {
            'belum_mulai' => 'Belum Mulai',
            'proses' => 'Proses',
            'selesai' => 'Selesai',
            'acc' => 'ACC',
            default => '-',
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status_tugas) {
            'belum_mulai' => 'gray',
            'proses' => 'yellow',
            'selesai' => 'blue',
            'acc' => 'green',
            default => 'gray',
        };
    }
}
