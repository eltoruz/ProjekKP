<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class MetadataUser extends Model
{
    protected $table = 'metadata_user';
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'id', 'kerjasama_id', 'metadata_id', 'user_id',
        'alasan', 'is_masked', 'approval_status', 'catatan_admin', 'soft_delete',
        'create_date', 'last_update',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (!$model->getKey()) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }
            $model->create_date ??= now();
            $model->last_update ??= now();
        });
        static::updating(function ($model) {
            $model->last_update = now();
        });
    }

    public function metadata()
    {
        return $this->belongsTo(Metadata::class, 'metadata_id', 'id');
    }

    public function kerjasama()
    {
        return $this->belongsTo(Kerjasama::class, 'kerjasama_id', 'kerjasama_id');
    }
}
