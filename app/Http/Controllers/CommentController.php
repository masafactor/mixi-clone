<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Community;
use App\Models\Topic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store(Request $request, Community $community, Topic $topic) // 👈 Community 追加
    {
        // フロントは body を送ってるのでキー名も body に合わせる
        $validated = $request->validate([
            'body' => 'required|string|max:1000',
        ]);

        $topic->comments()->create([
            'user_id' => Auth::id(),
            'body'    => $validated['body'],
        ]);

        return back()->with('success', 'コメントを投稿しました');
    }

    public function update(Request $request, Community $community, Topic $topic, Comment $comment)
    {
        $validated = $request->validate([
            'body' => ['required','string','max:1000'],
        ]);

        $comment->update($validated);
        return back()->with('success', 'コメントを更新しました。');
    }

    public function destroy(Community $community, Topic $topic, Comment $comment)
    {
        $comment->delete();
        return back()->with('success', 'コメントを削除しました。');
    }
}
