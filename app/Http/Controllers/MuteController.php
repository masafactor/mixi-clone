<?php

namespace App\Http\Controllers;

use App\Models\Mute;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MuteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
         $mutes = Mute::with('mutedUser')
                     ->where('user_id', Auth::id())
                     ->get();

        return view('mutes.index', compact('mutes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Mute::firstOrCreate([
            'user_id' => Auth::id(),
            'muted_user_id' => $request->muted_user_id,
        ]);

        return back()->with('message', 'ユーザーをミュートしました。');
    }

    /**
     * Display the specified resource.
     */
    public function show(Mute $mute)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Mute $mute)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Mute $mute)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Mute $mute)
    {
        // 自分のブロックかどうか確認（セキュリティ）
    if ($mute->user_id !== Auth::id()) {
        abort(403);
    }

    $mute->delete();

    return back()->with('message', 'ブロックを解除しました。');

    }
}
