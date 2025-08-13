<?php

namespace App\Http\Controllers;

use App\Models\Diary;
use App\Models\FriendUser;
use App\Models\Topic;
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
 $communities = $user->communities()
        // 承認制ならコメントアウト外す
        // ->wherePivot('status', 'approved')
        ->select('communities.id', 'communities.name')
        ->orderBy('communities.name')
        ->get();




        $user = Auth::user();

    // // 1. 友達の日記（新着順）
    $friendIds = $user->friends()->pluck('users.id');
    
    $friendDiaries = Diary::with('user:id,username') // ← これ追加
    ->whereIn('user_id', $friendIds)
    ->where(function ($q) {
        $q->where('visibility', 'public')
          ->orWhere('visibility', 'friends');
    })
    ->latest()
    ->take(10)
    ->get()
    ->map(function($d) {
        return [
            'id'         => $d->id,
            'type'       => 'diary',
            'title'      => $d->title,
            'created_at' => $d->created_at->toISOString(),
            'user'       => [
                'id'   => $d->user_id,
                'name' => $d->user?->username,
            ],
            'link'       => route('diary.show', $d->id),
        ];
    });

    // // 2. 所属コミュニティのトピック
    $communityIds = $user->communities()->pluck('communities.id');
$communityTopics = Topic::with('community:id,name') // ← コミュ名を一緒に取得
    ->whereIn('community_id', $communityIds)
    ->latest()
    ->take(10)
    ->get()
    ->map(function ($t) {
        return [
            'id'         => $t->id,
            'type'       => 'topic',
            'title'      => $t->title,
            'created_at' => $t->created_at->toISOString(),
            'community'  => [
                'id'   => $t->community_id,
                'name' => $t->community?->name,
            ],
            'link' => route('topics.show', [
            'community' => $t->community_id,
            'topic'     => $t->id,
            ]),
        ];
    });

    // // タイムラインをマージ＆日付順にソート
$timeline = $friendDiaries
    ->concat($communityTopics)          // ← concat でも merge でもOK（両方コレクションなら）
    ->sortBy(function ($i) {
        $pri = ($i['type'] ?? 'topic') === 'diary' ? 0 : 1; // 0=日記, 1=トピ
        return [$pri, -strtotime($i['created_at'])];
    })
    ->values();



    return Inertia::render('Home', [
        'latestDiary' => $latestDiary,
        'userName' => $user->username,
        'friendRequests' => $friendRequests,
        'communities' => $communities,
        'timeline' => $timeline,
    ]);
}

}
