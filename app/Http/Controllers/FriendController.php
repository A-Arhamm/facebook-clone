<?php

namespace App\Http\Controllers;

use App\Models\Friend;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class FriendController extends Controller
{
    public function addFriend($friendId)
    {
        $userId = Auth::id();

        if (Auth::user()->isFriendWith($friendId)) {
            return response()->json(['message' => 'Already friends'], 400);
        }

        Friend::create([
            'user_id' => $userId,
            'friend_id' => $friendId,
        ]);
    }

    public function removeFriend($friendId)
    {
        $userId = Auth::id();

        $friendship = Friend::where('user_id', $userId)->where('friend_id', $friendId)->first();

        if ($friendship) {
            $friendship->delete();
        }
    }

    public function checkFriendStatus($friendId)
    {
        $isFriend = Auth::user()->isFriendWith($friendId);
        return response()->json(['isFriend' => $isFriend]);
    }
}
