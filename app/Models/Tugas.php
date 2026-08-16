<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tugas extends Model
{
    protected $table = 'tugases';

    protected $primaryKey = 'id_tugas';

    public $incrementing = true;

    protected $keyType = 'int';

    protected $fillable = [
        'nomor_nota',
        'id_pegawai',
        'nama_tugas',
        'format',
        'disiarkan',
        'tanggal_produksi',
        'waktu_produksi',
        'tempat_produksi',
        'tanggal_rapat',
        'waktu_rapat',
        'tempat_rapat',
        'tempat',
        'narasumber',
        'topik',
        'penanggung_jawab',
        'supervisor',
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

    public function crews(): HasMany
    {
        return $this->hasMany(TugasCrew::class, 'id_tugas', 'id_tugas');
    }

    public function scopeForPegawai($query, int $pegawaiId)
    {
        return $query->where(function ($q) use ($pegawaiId) {
            $q->where('id_pegawai', $pegawaiId)
                ->orWhereHas('crews', fn ($cq) => $cq->where('id_pegawai', $pegawaiId));
        });
    }

    public function getCrewNamesByRole(string $peran): string
    {
        $names = $this->crews
            ->where('peran', $peran)
            ->map(fn ($crew) => $crew->pegawai->nama_pegawai ?? null)
            ->filter()
            ->implode(', ');

        return $names ?: '-';
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
