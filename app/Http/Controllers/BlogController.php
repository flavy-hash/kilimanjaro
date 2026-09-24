<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\View\View;

class BlogController extends Controller
{
    public function index(): View
    {
        $posts = Post::published()->ordered()->paginate(9);

        return view('blog', [
            'posts' => $posts->through(fn ($post) => $post->toCardArray()),
        ]);
    }

    public function show(Post $post): View
    {
        abort_unless($post->is_published && $post->published_at?->isPast(), 404);

        $related = Post::published()
            ->ordered()
            ->whereKeyNot($post->getKey())
            ->take(3)
            ->get()
            ->map
            ->toCardArray();

        return view('blog-post', [
            'post' => $post,
            'related' => $related,
        ]);
    }
}
