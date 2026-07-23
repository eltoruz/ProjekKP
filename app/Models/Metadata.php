<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Metadata extends Model
{
    protected $table = 'metadata';
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'id', 'db_name', 'schema_name', 'tbl_name', 'name',
        'type', 'type_name', 'description', 'seq',
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
    }

    public function userSelections()
    {
        return $this->hasMany(MetadataUser::class, 'metadata_id', 'id');
    }
}
