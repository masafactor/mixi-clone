<?php

namespace App\Http\Controllers;

use App\Models\Community;
use Illuminate\Support\Facades\Auth;

class CommunityMemberController extends Controller
{
    public function join(Community $community)
    {
        if ($community->users->contains(Auth::id())) {
            return back()->with('success', 'すでに参加しています');
        }

        $role = $community->visibility === 'approval' ? 'pending' : 'member';

        $community->users()->attach(Auth::id(), ['role' => $role]);

        return back()->with('success', $role === 'pending' ? '参加申請を送りました' : '参加しました');
    }

    public function leave(Community $community)
    {
        $community->users()->detach(Auth::id());
        return back()->with('success', '退会しました');
    }

    // 承認
    public function approveMember(Community $community, $userId)
    {
        $adminPivot = $community->users()->where('user_id', Auth::id())->first()?->pivot;
        if (!$adminPivot || $adminPivot->role !== 'admin') {
            abort(403, '管理者権限がありません');
        }

        $community->users()->updateExistingPivot($userId, ['role' => 'member']);

        return back()->with('success', 'メンバーを承認しました');
    }

    // 拒否
    public function rejectMember(Community $community, $userId)
    {
        $adminPivot = $community->users()->where('user_id', Auth::id())->first()?->pivot;
        if (!$adminPivot || $adminPivot->role !== 'admin') {
            abort(403, '管理者権限がありません');
        }

        $community->users()->detach($userId);

        return back()->with('success', '参加申請を拒否しました');
    }
    
}

