<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KsImplementasi extends Model
{
    protected $table = 'ks_implementasi';
    protected $fillable = ['nama_status'];

    public function kerjasama()
    {
        return $this->hasMany(Kerjasama::class, 'ks_implementasi');
    }
}
