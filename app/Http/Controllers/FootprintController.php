<?php

namespace App\Http\Controllers;

use App\Models\Footprint;
use App\Models\User;
use App\Models\Diary;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;

class FootprintController extends Controller
{
    /**
     * 自分に付いた足あと一覧
     */
public function index(Request $request)
    {
        $footprints = Footprint::with(['viewer' => function ($q) {
                // アクセサ用に profile_photo_path / name を含める
                $q->select('id','name','username','profile_photo_path');
            }])
            ->where('visited_user_id', $request->user()->id)
            ->orderByDesc('updated_at')
            ->paginate(20)
            ->through(function ($fp) {
                $v = $fp->viewer;
                return [
                    'id'         => $fp->id,
                    'updated_at' => $fp->updated_at->toIso8601String(),
                    'viewer'     => [
                        'id'       => $v->id,
                        'name'     => $v->username,            // 表示統一（好みで name に）
                        'username' => $v->username,
                        'icon'     => $v->profile_photo_url,   // ← アイコンURL
                        'link'     => route('users.show', $v->username),
                    ],
                ];
            });

        return inertia('Footprints/Index', ['footprints' => $footprints]);
    }


    /**
     * プロフィール閲覧の足あと（1日1件）
     */
    public function storeProfile(User $user, Request $request)
    {
        $viewerId = $request->user()->id;
        if ($viewerId === $user->id) {
            return response()->noContent(); // 自分は記録しない
        }

        $today = now()->toDateString();

        // 同日ユニーク制約（viewer_id, visited_user_id, visited_on）を想定
        try {
            Footprint::updateOrCreate(
                [
                    'viewer_id'       => $viewerId,
                    'visited_user_id' => $user->id,
                    'visited_on'      => $today,
                ],
                [
                    'updated_at'      => now(), // 既存なら更新時刻だけ上げる
                ]
            );
        } catch (QueryException $e) {
            // まれな競合時の保険（ユニーク制約レース）
            // 既存を触るだけ
            Footprint::where([
                'viewer_id'       => $viewerId,
                'visited_user_id' => $user->id,
                'visited_on'      => $today,
            ])->limit(1)->update(['updated_at' => now()]);
        }

        return  back(status: 303);
    }

    /**
     * 日記閲覧の足あと（1日1件、所有者に付与）
     */
    public function storeDiary(Diary $diary, Request $request)
    {
        $ownerId  = $diary->user_id;
        $viewerId = $request->user()->id;

        if ($viewerId === $ownerId) {
            return response()->noContent();
        }

        $today = now()->toDateString();

        try {
            Footprint::updateOrCreate(
                [
                    'viewer_id'       => $viewerId,
                    'visited_user_id' => $ownerId,
                    'visited_on'      => $today,
                ],
                [
                    'updated_at'      => now(),
                ]
            );
        } catch (\Illuminate\Database\QueryException $e) {
            Footprint::where([
                'viewer_id'       => $viewerId,
                'visited_user_id' => $ownerId,
                'visited_on'      => $today,
            ])->limit(1)->update(['updated_at' => now()]);
        }

        return back(status: 303);
    }

    /**
     * 足あと削除（自分宛のみ）
     */
    public function destroy(Footprint $footprint, Request $request)
    {
        abort_unless($footprint->visited_user_id === $request->user()->id, 403);
        $footprint->delete();

        return back()->with('success', '足あとを削除しました');
    }
}
