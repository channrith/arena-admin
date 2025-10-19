<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'author_id',
        'status',
        'is_special',
        'is_promotion',
        'source',
        'published_at'
    ];

    protected $casts = [
        'is_special' => 'boolean',
        'is_promotion' => 'boolean',
        'published_at' => 'datetime',
    ];

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function translations()
    {
        return $this->hasMany(PostTranslation::class);
    }

    public function currentTranslation()
    {
        $locale = app()->getLocale(); // or session('locale', 'en')
        return $this->hasOne(PostTranslation::class)->where('language_code', $locale);
    }


    public function scopePublished($query)
    {
        return $query->where('status', 'approved')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    public function scopeByAuthor($query, $authorId)
    {
        return $query->where('author_id', $authorId);
    }
}
