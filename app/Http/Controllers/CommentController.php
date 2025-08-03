<?php

namespace App\Http\Controllers;

use App\Models\Topic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store(Request $request, Topic $topic)
    {
        $validated = $request->validate([
            'body' => 'required|string',
        ]);

        $topic->comments()->create([
            'user_id' => Auth::id(),
            'body'    => $validated['body'],
        ]);

        return back()->with('success', 'コメントを投稿しました。');
    }
}
