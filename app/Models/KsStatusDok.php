<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KsStatusDok extends Model
{
    protected $table = 'ks_status_dok';
    protected $fillable = ['nama_status'];

    public function kerjasama()
    {
        return $this->hasMany(Kerjasama::class, 'ks_status_dok');
    }
}
