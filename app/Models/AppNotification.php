<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class AppNotification extends Model
{
    protected $table = 'app_notifications';
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'kerjasama_id',
        'target_role',
        'title',
        'message',
        'type',
        'url',
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
