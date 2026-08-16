<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Jabatan extends Model
{
    protected $primaryKey = 'id_jabatan';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = ['nama_jabatan'];

    public function pegawais(): HasMany
    {
        return $this->hasMany(Pegawai::class, 'id_jabatan');
    }
}
