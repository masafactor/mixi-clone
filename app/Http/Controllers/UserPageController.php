<?php

namespace App\Http\Controllers;

use App\Models\Diary;
use App\Models\FriendUser;
use App\Models\Prefecture;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Symfony\Component\HttpKernel\Profiler\Profile;

class UserPageController extends Controller
{
    public function show($username)
{
$user = User::with('profile')->where('username', $username)->firstOrFail();
$viewer = auth()->user();
$isOwner = $viewer && $viewer->id === $user->id;

$friendStatus = 'none';
$isFriend = false;

if ($viewer && !$isOwner) {
    $friend = FriendUser::where(function ($q) use ($user, $viewer) {
            $q->where('sender_id', $viewer->id)
              ->where('receiver_id', $user->id);
        })
        ->orWhere(function ($q) use ($user, $viewer) {
            $q->where('sender_id', $user->id)
              ->where('receiver_id', $viewer->id);
        })
        ->first();

    if ($friend) {
        if ($friend->status === 'pending') $friendStatus = 'pending';
        elseif ($friend->status === 'accepted') {
            $friendStatus = 'friend';
            $isFriend = true;
        }
    }
}

$profile = $user->profile;
if ($profile) {
    if ($profile->bio_visibility === 'private' && !$isOwner) $profile->bio = null;
    elseif ($profile->bio_visibility === 'friends' && !$isOwner && !$isFriend) $profile->bio = null;

    if ($profile->gender_visibility === 'private' && !$isOwner) $profile->gender = null;
    elseif ($profile->gender_visibility === 'friends' && !$isOwner && !$isFriend) $profile->gender = null;

    if ($profile->birthdate_visibility === 'private' && !$isOwner) $profile->birthdate = null;
    elseif ($profile->birthdate_visibility === 'friends' && !$isOwner && !$isFriend) $profile->birthdate = null;

    if ($profile->location_visibility === 'private' && !$isOwner) $profile->location = null;
    elseif ($profile->location_visibility === 'friends' && !$isOwner && !$isFriend) $profile->location = null;
}


return Inertia::render('Users/Show', [
    'profileUser'  => [
            'id'       => $user->id,
            'name'     => $user->name,
            'username' => $user->username,
            'icon'     => $user->profile_photo_url, // ★ ここに入れる
            'profile'  => $user->profile,           // 可視性加工後のprofile
        ], // profile を含んだ状態で渡す
    'isOwner'      => $isOwner,
    'friendStatus' => $friendStatus,
    'diaries'      => Diary::where('user_id', $user->id)->latest()->get(),
    'prefectures'  => Prefecture::all(['id','name']),
]);



}
    
}
