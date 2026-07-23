<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Metadata extends Model
{
    use HasFactory;

    protected $table = 'metadata';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $guarded = [];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = (string) Str::uuid();
            }
        });
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'metadata_user', 'metadata_id', 'user_id')
                    ->withPivot(['id', 'is_masked', 'soft_delete'])
                    ->withTimestamps();
    }
}
