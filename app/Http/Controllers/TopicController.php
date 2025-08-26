<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Community;
use App\Models\Topic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class TopicController extends Controller
{
    public function index(Community $community)
    {
        // 公開設定チェック（メンバーのみ閲覧可ならここで判定）
        return Inertia::render('Topics/Index', [
            'community' => $community->load('category'),
            'topics'    => $community->topics()->with('user')->latest()->get(),
            
        ]);
    }

    
    public function create(Community $community)
    {
        return Inertia::render('Topics/Create', [
            'community' => $community,
        ]);
    }

    public function store(Request $request, Community $community)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'body'  => 'required|string',
        ]);

        $topic = $community->topics()->create([
            'user_id' => Auth::id(),
            'title'   => $validated['title'],
            'body'    => $validated['body'],
        ]);

        return redirect()->route('topics.show', [$community->id, $topic->id])
                         ->with('success', 'トピックを作成しました。');
    }

    public function show(Community $community, Topic $topic)
    {
        $topic->load([
        'user',                // トピック作成者
        'comments.user'        // コメントとその投稿者
    ]);

    return inertia('Topics/Show', [
        'community' => $community->only(['id', 'username','profile_photo_path']),
        'topic'     => $topic,
        'comments'  => $topic->comments
    ]);
    
    }


   

    // public function show($id)
    // {
    //     $topic = Topic::with('user')->findOrFail($id);
    //     $comments = Comment::with('user')->where('topic_id', $id)->latest()->get();

    //     return Inertia::render('Topics/Show', [
    //         'topic' => $topic,
    //         'comments' => $comments,
    //     ]);
    // }
}
