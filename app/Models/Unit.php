<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Unit extends Model
{
    protected $primaryKey = 'id_unit';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = ['nama_unit'];

    public function pegawais(): HasMany
    {
        return $this->hasMany(Pegawai::class, 'id_unit');
    }
}
