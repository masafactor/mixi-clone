<?php

namespace App\Http\Controllers;

use App\Models\Diary;
use App\Models\FriendUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class HomeController extends Controller
{
    
    public function index()
{
    $user = Auth::user();

    // 自分の日記最新1件（オプション）
    $latestDiary = Diary::where('user_id', Auth::id())
                    ->latest()
                    ->first();


    $friendRequests = FriendUser::with('sender')
        ->where('receiver_id', $user->id)
        ->where('status', 'pending')
        ->get();

    return Inertia::render('Home', [
        'latestDiary' => $latestDiary,
        'userName' => $user->username,
        'friendRequests' => $friendRequests
    ]);
}

}
