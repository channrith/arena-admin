<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostHighlight extends Model
{
    protected $fillable = [
        'post_id',
        'created_by',
        'type',
        'priority',
        'start_at',
        'end_at',
        'created_by'
    ];

    protected $casts = [
        'start_at' => 'datetime',
        'end_at' => 'datetime',
    ];

    // Each highlight belongs to one post
    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    // The user who created/assigned this highlight
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Scope: active highlights only
    public function scopeActive($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('start_at')
                ->orWhere('start_at', '<=', now());
        })
            ->where(function ($q) {
                $q->whereNull('end_at')
                    ->orWhere('end_at', '>=', now());
            });
    }

    // Scope: filter by type (special/promotion)
    public function scopeType($query, string $type)
    {
        return $query->where('type', $type);
    }
}
