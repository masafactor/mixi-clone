<?php

namespace App\Http\Controllers;

use App\Models\Prefecture;
use App\Models\UserProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class UserProfileController extends Controller
{

public function editAvatar()
{
    return Inertia::render('Profile/AvatarUpload', [
        'user' => auth()->user(),
        'profile' => auth()->user()->profile,
    ]);
}

public function uploadAvatar(Request $request)
{
    $data = $request->validate([
        'avatar' => 'required|image|max:2048',
    ]);

    $profile = auth()->user()->profile;

    $path = $request->file('avatar')->store('profile-images', 'public');
    $profile->profile_image_path = $path;
    $profile->save();

    return redirect()->route('profile.avatar.edit')->with('success', '画像を更新しました');
}

    /**
     * ユーザーの公開プロフィール編集画面を表示する
     */
    public function edit()
    {
        $user = Auth::user();

        // 認証ユーザーのプロフィール情報を取得（なければ作成）
        $profile = $user->profile ?? UserProfile::create(['user_id' => $user->id]);
        $prefectures = Prefecture::orderBy('id')->get();

        Log::info(json_encode($prefectures));
      
        return inertia('Profile/Edit', [
            'profile' => $profile,
            'prefectures' => $prefectures,
        ]);
    }

   public function update(Request $request)
{
    $user = Auth::user();

    // バリデーション
    $validated = $request->validate([
        'nickname' => 'nullable|string|max:255',
        'nickname_visibility' => 'required|string',
        'bio' => 'nullable|string',
        'bio_visibility' => 'required|string',
        'gender' => 'nullable|string',
        'gender_visibility' => 'required|string',
        'birthdate' => 'nullable|date',
        'birthdate_visibility' => 'required|string',
        'location' => 'nullable|string|max:255',
        'location_visibility' => 'required|string',
        'avatar' => 'nullable|image|max:2048', 
        'prefecture_id' => 'nullable|integer|exists:prefectures,id',
    ]);

    // 画像の保存
    if ($request->hasFile('avatar')) {
        $validated['profile_image_path'] = $request->file('avatar')
            ->store('profile-images', 'public');
    }

    // プロフィール更新 or 作成
    $profile = $user->profile()->updateOrCreate([], $validated);

    return redirect()->route('profile.edit')
        ->with('success', 'プロフィールを更新しました');
}


    public function editIcon(Request $request)
    {
        // 画面表示だけ。処理は Jetstream の既存エンドポイントに投げる
        return inertia('Profile/Icon', [
            'user' => $request->user()->only('name','email','profile_photo_url'),
        ]);
    }

}