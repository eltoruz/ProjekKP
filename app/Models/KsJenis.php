<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KsJenis extends Model
{
    protected $table = 'ks_jenis';
    protected $fillable = ['nama_jenis'];

    public function kerjasama()
    {
        return $this->hasMany(Kerjasama::class, 'ks_jenis');
    }
}
