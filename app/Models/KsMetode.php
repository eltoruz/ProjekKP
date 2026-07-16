<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KsMetode extends Model
{
    protected $table = 'ks_metode';
    protected $fillable = ['nama_metode'];

    public function kerjasama()
    {
        return $this->hasMany(Kerjasama::class, 'ks_metode');
    }
}
