<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KsTingkat extends Model
{
    protected $table = 'ks_tingkat';
    protected $fillable = ['nama_tingkat'];

    public function kerjasama()
    {
        return $this->hasMany(Kerjasama::class, 'ks_tingkat');
    }
}
