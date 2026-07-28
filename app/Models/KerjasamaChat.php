<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class KerjasamaChat extends Model
{
    protected $table = 'kerjasama_chats';
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'kerjasama_id',
        'sender_role',
        'sender_name',
        'pesan',
        'attachment_path',
        'is_read',
    ];

    protected $casts = [
        'is_read' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (!$model->getKey()) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }
        });
    }

    public function kerjasama()
    {
        return $this->belongsTo(Kerjasama::class, 'kerjasama_id', 'kerjasama_id');
    }
}
