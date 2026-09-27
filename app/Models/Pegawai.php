<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Pegawai extends Model
{
    protected $primaryKey = 'id_pegawai';

    public $incrementing = true;

    protected $keyType = 'int';

    protected $fillable = [
        'id_unit',
        'id_jabatan',
        'nip',
        'nama_pegawai',
        'jenis_kelamin',
        'no_hp',
        'alamat',
    ];

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class, 'id_unit', 'id_unit');
    }

    public function jabatan(): BelongsTo
    {
        return $this->belongsTo(Jabatan::class, 'id_jabatan', 'id_jabatan');
    }

    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'pegawai_id');
    }

    public function tugases(): HasMany
    {
        return $this->hasMany(Tugas::class, 'id_pegawai');
    }

    public function tugasCrews(): HasMany
    {
        return $this->hasMany(TugasCrew::class, 'id_pegawai', 'id_pegawai');
    }
}
