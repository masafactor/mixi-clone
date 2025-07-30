<?php

namespace App\Http\Controllers;

use App\Models\Block;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BlockController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $blocks = Block::with('blockedUser')
                       ->where('user_id', Auth::id())
                       ->get();

        return view('blocks.index', compact('blocks'));
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
        Block::firstOrCreate([
            'user_id' => Auth::id(),
            'blocked_user_id' => $request->blocked_user_id,
        ]);

        return back()->with('message', 'ユーザーをブロックしました。');
    }

    /**
     * Display the specified resource.
     */
    public function show(Block $block)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Block $block)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Block $block)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Block $block)
    {
        // 自分のブロックかどうか確認（セキュリティ）
    if ($block->user_id !== Auth::id()) {
        abort(403);
    }

    $block->delete();

    return back()->with('message', 'ブロックを解除しました。');
    }
}
