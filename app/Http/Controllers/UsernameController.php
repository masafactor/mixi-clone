<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class UsernameController extends Controller
{
    public function create()
    {
        // return Inertia::render('Users/SetUsername');
        return Inertia::render('Users/SetUsername', [
    'currentNickname' => Auth::user()->username, // GitHubから取得した仮ユーザー名
]);
    }

    public function store(Request $request)
    {
        $user = $request->user();

        $request->validate([
                'username' => [
                    'required',
                    'string',
                    'alpha_dash',
                    'min:3',
                    'max:30',
                    Rule::unique('users', 'username')->ignore($user->id), // 他のユーザーと重複禁止
                    function ($attribute, $value, $fail) use ($user) {
                        if ($value === $user->username) {
                            $fail('GitHubのユーザー名とは違う名前を入力してください。');
                        }
                    },
                ],
            ]);

        $user = $request->user();
        $user->username = $request->username;
        $user->save();

        return to_route('dashboard')->with('success', 'ユーザー名を設定しました');
    }

    function generateIncrementalUsername(string $base): string
    {
    $candidate = $base;
    $i = 1;

    while (User::where('username', $candidate)->exists()) {
        $candidate = $base . $i++;
    }

    return $candidate;
    }
}
