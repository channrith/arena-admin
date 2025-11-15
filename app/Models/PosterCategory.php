<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PosterCategory extends Model
{
    protected $table = 'poster_categories';

    protected $fillable = [
        'title',
        'service_id',
        'remark',
    ];

    public $timestamps = false;

    public function posters()
    {
        return $this->hasMany(Poster::class, 'category_id');
    }
}
