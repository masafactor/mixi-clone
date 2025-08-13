<?php

namespace App\Http\Controllers;
use App\Models\Footprint;
use App\Models\User;
use App\Models\Diary;
use Illuminate\Http\Request;

class FootprintController extends Controller
{


    /**
     * 自分に付いた足あと一覧
     */
    public function index(Request $request)
    {
        $footprints = Footprint::with(['viewer:id,name'])
            ->where('visited_user_id', $request->user()->id)
            ->latest()
            ->paginate(20);

        // Inertia想定。Bladeなら view() に置き換え
        return inertia('Footprints/Index', [
            'footprints' => $footprints,
        ]);
    }

    /**
     * プロフィールを見た足あと記録
     * 1日1件だけ残す（同じ viewer → visited_user）
     */
    public function storeProfile(User $user, Request $request)
    {
        $viewerId = $request->user()->id;

        if ($viewerId === $user->id) {
            return response()->noContent(); // 自分のページは記録しない
        }

        // 今日すでに記録があるか
        $today = now()->toDateString();

        $exists = Footprint::where('viewer_id', $viewerId)
            ->where('visited_user_id', $user->id)
            ->whereDate('created_at', $today)
            ->exists();

        if (! $exists) {
            Footprint::create([
                'viewer_id'       => $viewerId,
                'visited_user_id' => $user->id,
            ]);
        } else {
            // 「最新に上げたい」場合は最終訪問時間を更新
            Footprint::where('viewer_id', $viewerId)
                ->where('visited_user_id', $user->id)
                ->whereDate('created_at', $today)
                ->update(['updated_at' => now()]);
        }

        return response()->noContent();
    }

    /**
     * 日記詳細を見た足あと記録（オプション）
     * 「誰のページを見たか」は日記の所有者で判定
     */
    public function storeDiary(Diary $diary, Request $request)
    {
        $ownerId  = $diary->user_id;
        $viewerId = $request->user()->id;

        if ($viewerId === $ownerId) {
            return response()->noContent(); // 自分の日記は記録しない
        }

        $today = now()->toDateString();

        $exists = Footprint::where('viewer_id', $viewerId)
            ->where('visited_user_id', $ownerId)
            ->whereDate('created_at', $today)
            ->exists();

        if (! $exists) {
            Footprint::create([
                'viewer_id'       => $viewerId,
                'visited_user_id' => $ownerId,
            ]);
        } else {
            Footprint::where('viewer_id', $viewerId)
                ->where('visited_user_id', $ownerId)
                ->whereDate('created_at', $today)
                ->update(['updated_at' => now()]);
        }

        return response()->noContent();
    }

    /**
     * 足あとを削除（任意：自分に付いたレコードのみ）
     */
    public function destroy(Footprint $footprint, Request $request)
    {
        abort_unless($footprint->visited_user_id === $request->user()->id, 403);
        $footprint->delete();

        return back()->with('success', '足あとを削除しました');
    }
}
