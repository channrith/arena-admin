<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'author_id',
        'created_by',
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

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function services()
    {
        return $this->belongsToMany(Service::class, 'post_service');
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

    public function highlights()
    {
        return $this->hasMany(PostHighlight::class);
    }

    public function activeHighlights()
    {
        return $this->hasMany(PostHighlight::class)
            ->active()
            ->orderBy('priority');
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

    public function getDisplayStatusAttribute()
    {
        if ($this->status === 'approved') {
            if ($this->published_at && $this->published_at->isFuture()) {
                return 'Scheduled';
            }

            if ($this->published_at && $this->published_at->isPast()) {
                return 'Published';
            }
        }

        return ucfirst($this->status);
    }
}
