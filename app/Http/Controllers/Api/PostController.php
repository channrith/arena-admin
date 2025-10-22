<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\PostHighlight;
use Illuminate\Http\Request;

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
        $highlights = PostHighlight::with(['post.currentTranslation'])
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
}
