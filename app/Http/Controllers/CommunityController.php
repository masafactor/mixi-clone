<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Community;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommunityController extends Controller
{
    public function index()
    {
        $user = Auth::user();

    $communities = Community::withCount('users')
        ->where(function ($query) use ($user) {
            $query->where('visibility', 'public')
                  ->orWhere(function ($q) use ($user) {
                      // 承認制・メンバーの場合
                      $q->where('visibility', 'approval')
                        ->whereHas('users', function ($q2) use ($user) {
                            $q2->where('user_id', $user->id);
                        });
                  })
                  ->orWhere(function ($q) use ($user) {
                      // プライベートでもオーナーなら見れる
                      $q->where('visibility', 'private')
                        ->where('owner_id', $user->id);
                  });
        })
        ->get();

    return inertia('Communities/Index', [
        'communities' => $communities,
    ]);
    }

    // 作成フォーム
    public function create()
    {
        $categories = Category::all();
        return inertia('Communities/Create', [
            'categories' => $categories
        ]);
    }

    // 新規作成処理
    public function store(Request $request)
    {
        $validated = $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'visibility' => 'required|in:public,approval,private',
        'category_id' => 'nullable|exists:categories,id',
    ]);

    $community = Community::create([
        'name'        => $validated['name'],
        'description' => $validated['description'],
        'visibility'  => $validated['visibility'],
        'category_id' => $validated['category_id'],
        'owner_id'    => Auth::id(),
    ]);

    // 作成者を管理者として登録
    $community->users()->attach(Auth::id(), ['role' => 'admin']);

    
    return redirect()->route('communities.show', $community->id)
                     ->with('success', 'コミュニティを作成しました');
    }

    // 詳細表示
    public function show(Community $community)
    {

  $community->load([
            'category:id,name', // category は name だけあれば十分なら
            'users:id,name', // ユーザー一覧は id と name だけ
            'topics' => function ($query) {
                $query->select('id', 'community_id', 'title', 'body', 'user_id', 'created_at')
                    ->with(['user:id,name', 'comments' => function ($query) {
                        $query->select('id', 'topic_id', 'user_id', 'body', 'created_at')
                                ->with('user:id,name');
                    }]);
            }
        ]);

        // $community->load(['category',
        //  'users',
        //  'topics.user',        // トピック作成者
        // 'topics.comments.user' // コメントとコメントの作成者
        // ]);

        $user = Auth::user();
        $isMember = false;
        $isPending = false;
        $isAdmin = false;

        if ($user) {
            $pivot = $community->users->find($user->id)?->pivot;
            if ($pivot) {
                $isAdmin = $pivot->role === 'admin';
                $isMember = $pivot->role === 'member' || $pivot->role === 'admin';
                $isPending = $pivot->role === 'pending';
            }
        }

        return inertia('Communities/Show', [
            'community'   => $community,
            'visibility'  => $community->visibility,
            'isMember'    => $isMember,
            'isPending'   => $isPending,
            'isAdmin'     => $isAdmin,
            'membersCount'=> $community->users->where('pivot.role', 'member')->count(),
            'members'     => $community->users->where('pivot.role', 'member')->values(),
            'topics'      => $community->topics,
            'pendingMembers' => $isAdmin ? $community->users()->wherePivot('role', 'pending')->get() : [],
        ]);
    }

}
