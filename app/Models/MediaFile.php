<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class MediaFile extends Model
{
    protected $fillable = [
        'uuid',
        'original_name',
        'file_name',
        'url',
        'mime_type',
        'size',
        'category',
        'owner_type',
        'owner_id',
        'uploader_id',
        'meta',
    ];

    protected $casts = [
        'meta' => 'array',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (!$model->uuid) {
                $model->uuid = (string) Str::uuid();
            }
        });
    }

    public function owner()
    {
        return $this->morphTo();
    }
}
