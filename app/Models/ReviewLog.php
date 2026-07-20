<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReviewLog extends Model
{
    protected $fillable = [
        'kerjasama_id',
        'label',
        'catatan',
    ];

    public function kerjasama()
    {
        return $this->belongsTo(Kerjasama::class, 'kerjasama_id', 'kerjasama_id');
    }
}
