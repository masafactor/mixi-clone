<?php

namespace App\Http\Controllers;

use App\Models\Diary;
use App\Models\Footprint;
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
    $friendIds = $user->friends()->pluck('users.id',);
    
    $friendDiaries = Diary::with(['user' => function ($q) {
        // アクセサ計算に name / profile_photo_path を使うので残す
        $q->select('id','username','name','profile_photo_path');
    }])
    ->whereIn('user_id', $friendIds)
    ->where(fn($q) => $q->where('visibility','public')->orWhere('visibility','friends'))
    ->latest()
    ->take(10)
    ->get()
    ->map(function ($d) {
        $u = $d->user;
        return [
            'id'         => $d->id,
            'type'       => 'diary',
            'title'      => $d->title,
            'created_at' => $d->created_at->toISOString(),
            'user'       => [
                'id'    => $d->user_id,
                'name'  => $u?->username,
                // ← これをフロントに渡す（Jetstreamのアクセサ。無ければデフォルト画像URL）
                'icon'  => $u?->profile_photo_url,
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
$footprints = Footprint::with(['viewer' => function ($q) {
        // アクセサ defaultProfilePhotoUrl() で name を使う場合があるので含める
        $q->select('id','name','username','profile_photo_path');
    }])
    ->where('visited_user_id', $user->id)
    ->orderByDesc('updated_at')
    ->limit(10)
    ->get(['id','viewer_id','visited_user_id','visited_on','updated_at'])
    ->map(function ($fp) {
        $v = $fp->viewer; // Userモデル
        return [
            'id'         => $fp->id,
            'visited_on' => optional($fp->visited_on)->toDateString(),
            'updated_at' => $fp->updated_at->toIso8601String(),
            'viewer'     => [
                'id'       => $v->id,
                'name'     => $v->username,
                'username' => $v->username,
                'icon'     => $v->profile_photo_url, // ← ここを修正！
            ],
        ];
    });



    return Inertia::render('Home', [
        'latestDiary' => $latestDiary,
        'userName' => $user->username,
        'friendRequests' => $friendRequests,
        'communities' => $communities,
        'timeline' => $timeline,
        'footprints' => $footprints,
    ]);
}

}
