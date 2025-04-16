<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    public function like(Post $post)
    {
        $like = Like::firstOrCreate([
            'post_id' => $post->id,
            'user_id' => Auth::id(),
        ]);

        return response()->json(['liked' => true, 'like_id' => $like->id]);
    }

    public function unlike(Post $post)
    {
        $like = Like::where('post_id', $post->id)->where('user_id', Auth::id())->first();

        if ($like) {
            $like->delete();
            return response()->json(['liked' => false]);
        }
    }
}
