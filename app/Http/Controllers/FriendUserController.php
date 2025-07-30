<?php

namespace App\Http\Controllers;

use App\Models\FriendUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class FriendUserController extends Controller
{
    // フレンド申請送信
    public function store(Request $request)
    {
        $exists = FriendUser::where('sender_id', Auth::id())
                        ->where('receiver_id', $request->receiver_id)
                        ->exists();

    if ($exists) {
        return back()->with('success', 'すでにフレンド申請済みです。');
    }
        FriendUser::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $request->receiver_id,
            'status' => 'pending',
        ]);

        return back()->with('success', 'フレンド申請を送信しました。');
    }

    
    // 自分が受け取ったフレンド申請一覧
    public function index()
    {
        $requests = FriendUser::where('receiver_id', Auth::id())
                              ->where('status', 'pending')
                              ->with('sender')
                              ->get();

        return view('friend_requests.index', compact('requests'));
    }

    // フレンド承認
    public function update($id)
    {
        $request = FriendUser::findOrFail($id);
        if ($request->receiver_id !== Auth::id()) {
            abort(403);
        }

        $request->update(['status' => 'accepted']);

        return back()->with('message', 'フレンドを承認しました。');
    }

    // フレンド申請拒否 or 解除
    public function destroy($id)
    {
       $request = FriendUser::where('id', $id)
        ->where('receiver_id', Auth::id())
        ->where('status', 'pending')
        ->firstOrFail();

        $request->delete();

        return back()->with('message', 'フレンド申請を拒否しました。');
        
    }


    public function unfriend($id)
    {
        $friendship = FriendUser::where(function ($q) use ($id) {
                $q->where('sender_id', Auth::id())
                ->where('receiver_id', $id);
            })
            ->orWhere(function ($q) use ($id) {
                $q->where('receiver_id', Auth::id())
                ->where('sender_id', $id);
            })
            ->where('status', 'accepted')
            ->firstOrFail();

        $friendship->delete();

        return back()->with('message', 'フレンドを解除しました。');
    }
    
    public function requests()
    {
        $requests = FriendUser::with('sender')
            ->where('receiver_id', Auth::id())
            ->where('status', 'pending')
            ->get();

        return Inertia::render('FriendRequests/Index', [
            'requests' => $requests,
        ]);
    }
}
