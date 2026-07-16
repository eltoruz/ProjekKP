<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Surat extends Model
{
    protected $table = 'kerjasama';
    protected $primaryKey = 'kerjasama_id';
    protected $keyType = 'string';
    public $incrementing = false;

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope('suratOnly', function ($query) {
            $query->whereNotIn('ks_jenis', [3]);
        });
    }

    public function jenisSurat()
    {
        return $this->belongsTo(KsJenis::class, 'ks_jenis');
    }
}
