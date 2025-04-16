<?php

namespace App\Http\Controllers;

use App\Models\Follow;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FollowController extends Controller
{
    public function follow($followId)
    {
        $userId = Auth::id();

        if (Auth::user()->following($followId)) {
            return response()->json(['message' => 'Already following'], 400);
        }

        Follow::create([
            'user_id' => $userId,
            'follower_id' => $followId,
        ]);
    }

    public function unfollow($followId)
    {
        $userId = Auth::id();

        $follows = Follow::where('user_id', $userId)->where('follower_id', $followId)->first();

        if ($follows) {
            $follows->delete();
        }
    }

    public function checkFollowStatus($followId)
    {
        $isFollow = Auth::user()->following($followId);
        return response()->json(['isFollow' => $isFollow]);
    }
}
