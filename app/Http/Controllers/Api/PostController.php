<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\PostHighlight;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function getAllPosts()
    {
        $posts = Post::with([
            'author',
            'currentTranslation'
        ])
            ->published()
            ->orderBy('published_at', 'desc')
            ->paginate(15);

        return $posts;
    }

    public function getTopHighlight()
    {
        $highlights = PostHighlight::with([
            'post' => function ($query) {
                $query->select('id', 'author_id', 'source', 'status', 'published_at');
            },
            'post.currentTranslation' => function ($query) {
                $query->select('post_id', 'language_code', 'title', 'feature_image');
            },
        ])
            ->type('special')
            ->active()
            ->orderBy('priority')
            ->take(5)
            ->get();

        return $highlights;
    }

    /**
     * Display the specified resource.
     */
    public function getPostDetailById(string $id)
    {
        $post = Post::with([
            'author',
            'currentTranslation'
        ])
            ->published()
            ->findOrFail($id);

        //$post->highlights; // all highlights
        //$post->activeHighlights; // active ones sorted by priority

        return $post;
    }

    public function listForSitemap()
    {
        // Only load fields needed for sitemap (avoid heavy relationships)
        $posts = Post::select('id', 'updated_at')
            ->with([
                'currentTranslation:id,post_id,slug'
            ])
            ->published()
            ->orderBy('updated_at', 'desc')
            ->get();

        // Transform for cleaner output
        return $posts->map(function ($post) {
            return [
                'id' => $post->id,
                'slug' => $post->currentTranslation?->slug,
                'updated_at' => $post->updated_at->toIso8601String(),
            ];
        });
    }
}
