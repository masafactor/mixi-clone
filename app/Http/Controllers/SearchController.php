<?php

namespace App\Http\Controllers;

use App\Models\Community;
use App\Models\Diary;
use App\Models\User;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->input('q');

    
        // LIKEワイルドカードをエスケープ（%/_ をそのまま文字列として検索）
        $qLike = $this->likeEscape($q);


        $diaries = Diary::with('user')
            ->where('title','like',"%{$q}%")
            ->orWhere('body','like',"%{$q}%")
            ->latest()
            ->take(20)
            ->get();
       
        $communities = Community::where('name','like',"%{$q}%")
            ->orWhere('description','like',"%{$q}%")
            ->latest()
            ->take(20)
            ->get();

         // ユーザー（username と name の部分一致）
        $users = User::query()
            ->select('id', 'username', 'name', 'profile_photo_path') // Jetstream想定
            ->where(function ($query) use ($qLike) {
                $query->where('username', 'like', "%{$qLike}%")
                      ->orWhere('name', 'like', "%{$qLike}%");
            })
            ->orderByDesc('id')
            ->take(20)
            ->get()
            // Jetstream の accessor があれば不要。無い場合はフロント用に付与
            ->map(function ($u) {
                if (!isset($u->profile_photo_url) && method_exists($u, 'profilePhotoUrl')) {
                    $u->profile_photo_url = $u->profilePhotoUrl();
                }
                return $u;
            });

        return inertia('Search/Index', [
            'q'           => $q,
            'diaries'     => $diaries,
            'communities' => $communities,
            'users'       => $users,
        ]);
    }

    private function likeEscape(string $value): string
    {
        return addcslashes($value, '%_\\');
    }
}
