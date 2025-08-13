<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\CommunityController;
use App\Http\Controllers\CommunityMemberController;
use App\Http\Controllers\DiaryController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Laravel\Socialite\Facades\Socialite;
use Inertia\Inertia;

use App\Models\User;
use App\Http\Controllers\FriendUserController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TopicController;
use App\Http\Controllers\UsernameController;
use App\Http\Controllers\UserPageController;
use App\Http\Controllers\UserProfileController;

// トップページ（Blade表示）
Route::get('/', function () {
    return view('top');
});

// GitHub認証
Route::get('/auth/github', fn() => Socialite::driver('github')->redirect());

Route::get('/auth/github/callback', function () {
    $githubUser = Socialite::driver('github')->user();

    $user = User::firstOrCreate(
        ['email' => $githubUser->getEmail()],
        [
            'name' => $githubUser->getName() ?? $githubUser->getNickname(),
            'username' => $githubUser->getNickname(),
            'password' => bcrypt(uniqid()), // 仮パスワード
            'email_verified_at' => now(),
            
        ]
    );

    Auth::login($user);


    return redirect('/home');
});

// メール認証関連
Route::middleware(['auth'])->group(function () {
    Route::get('/email/verify', fn() => view('auth.verify-email'))->name('verification.notice');

    Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();

    $user = Auth::user();
    // usernameがGitHubのnicknameと同じなら初期状態と判断
    if ($user->username === $user->name) {
        return redirect()->route('username.register');
    }

        return redirect('/home');
    })->middleware(['signed'])->name('verification.verify');

    Route::post('/email/verification-notification', function () {
        request()->user()->sendEmailVerificationNotification();
        return back()->with('message', 'Verification link sent!');
    })->middleware(['throttle:6,1'])->name('verification.send');
});

// 認証ユーザー向けルート
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {


    // コミュニティ一覧
    Route::get('/communities', [CommunityController::class, 'index'])->name('communities.index');

    // コミュニティ作成フォーム
    Route::get('/communities/create', [CommunityController::class, 'create'])->name('communities.create');

    // コミュニティ登録
    Route::post('/communities', [CommunityController::class, 'store'])->name('communities.store');

    // コミュニティ詳細
    Route::get('/communities/{community}', [CommunityController::class, 'show'])->name('communities.show');

   
    // コミュニティ参加申請
    Route::post('/communities/{community}/join', [CommunityMemberController::class, 'join'])
        ->name('communities.join');
    Route::delete('/communities/{community}/leave', [CommunityMemberController::class, 'leave'])
        ->name('communities.leave');

    // 管理者専用承認機能
    Route::patch('/communities/{community}/approve/{user}', [CommunityMemberController::class, 'approveMember'])->name('communities.approve');
    Route::delete('/communities/{community}/reject/{user}', [CommunityMemberController::class, 'rejectMember'])->name('communities.reject');
    
    // フレンド機能
    
    Route::post('/friend-request', [FriendUserController::class, 'store'])->name('friend.request');
    Route::patch('/friend-request/{id}', [FriendUserController::class, 'update'])->name('friend.accept');
    Route::delete('/friend-request/{id}', [FriendUserController::class, 'destroy'])->name('friend.reject');
    Route::get('/friend-requests', [FriendUserController::class, 'requests'])->name('friend.requests.index');

    Route::delete('/friends/{id}', [FriendUserController::class, 'unfriend']) ->name('friend.unfriend');
    
    // 公開プロフィール編集（Inertiaで表示）
    Route::get('/profile/edit', function () {
        return Inertia::render('Profile/Edit', [
            'profile' => auth()->user()->profile,
        ]);
    })->name('profile.edit');

    Route::get('/profile/edit', [UserProfileController::class, 'edit'])->name('profile.edit');
    // 公開プロフィール更新処理
    Route::put('/profile', [UserProfileController::class, 'update'])->name('profile.update');
//   Route::match(['get', 'post'], '/profile/avatar', [UserProfileController::class, 'uploadAvatar']);



    Route::get('/diary', [DiaryController::class, 'index'])->name('diary.index');
    Route::get('/diary/create', [DiaryController::class, 'create'])->name('diary.create');
    Route::post('/diary', [DiaryController::class, 'store'])->name('diary.store');
    Route::get('/diary/{diary}', [DiaryController::class, 'show'])->name('diary.show');
    Route::get('/diary/{diary}/edit', [DiaryController::class, 'edit'])->name('diary.edit');
    Route::put('/diary/{diary}', [DiaryController::class, 'update'])->name('diary.update');
    // これがあるか確認

    Route::delete('/diary/{diary}', [DiaryController::class, 'destroy'])
    ->name('diary.destroy');
Route::get('/username-suggestion/{username}', function ($username) {
    $candidate = $username;
    $i = 1;
    while (\App\Models\User::where('username', $candidate)->exists()) {
        $candidate = $username . $i++;
    }
    return response()->json(['suggestion' => $candidate]);
});

Route::prefix('communities/{community}')->group(function () {
    Route::get('topics', [TopicController::class, 'index'])->name('topics.index');
    Route::get('topics/create', [TopicController::class, 'create'])->name('topics.create');
    Route::post('topics', [TopicController::class, 'store'])->name('topics.store');
    Route::get('topics/{topic}', [TopicController::class, 'show'])->name('topics.show');
});

// コメント
// Route::post('topics/{topic}/comments', [CommentController::class, 'store'])->name('comments.store');


Route::prefix('users')->group(function () {
    Route::get('/set-username', [UsernameController::class, 'create'])->name('username.register');
    Route::post('/set-username', [UsernameController::class, 'store'])->name('username.store');
});

// routes/web.php
Route::prefix('communities/{community}/topics/{topic}')->group(function () {
    Route::post('comments',  [CommentController::class, 'store'])->name('comments.store');
    Route::patch('comments/{comment}', [CommentController::class, 'update'])->name('comments.update');
    Route::delete('comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
});


Route::get('/home', [HomeController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('home');
});

Route::get('/users/{username}', [UserPageController::class, 'show'])
    ->name('users.show');
