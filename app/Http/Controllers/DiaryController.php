<?php

namespace App\Http\Controllers;

use App\Models\Diary;
use Illuminate\Http\Request;

use App\Http\Requests\StoreDiaryRequest;
use App\Http\Requests\UpdateDiaryRequest;
use Inertia\Inertia;

class DiaryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
        $diaries = Diary::where('user_id', auth()->id())->latest()->get();

        return Inertia::render('Diary/Index', [
            'diaries' => $diaries,
        ]);
    }

    

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
         return Inertia::render('Diary/Create'); 
    }

    /**
     * Store a newly created resource in storage.
     */
// app/Http/Controllers/DiaryController.php


public function store(StoreDiaryRequest $request)
{
    $validated = $request->validated();

    $request->user()->diaries()->create($validated);

    return to_route('diary.index')->with('success', '投稿が完了しました');
}


    /**
     * Display the specified resource.
     */
    public function show(\App\Models\Diary $diary)
    {
        $diary->loadMissing('user:id,username');
       
        return Inertia::render('Diary/Show', [
            'diary' => [
                'id'         => $diary->id,
                'title'      => $diary->title,
                'body'       => $diary->body,
                'visibility' => $diary->visibility,
                'created_at' => optional($diary->created_at)->toDateTimeString(),
                'user'       => [
                    'id'   => $diary->user_id,
                    'name' => $diary->user?->username,
                ],
            ],
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Diary $diary)
    {
      
        return Inertia::render('Diary/Edit', [
        'diary' => $diary,
    ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDiaryRequest $request, Diary $diary)
    {
        // $this->authorize('update', $diary); // 必要ならポリシーも

        $diary->update($request->validated());

        return to_route('diary.index')->with('success', '更新が完了しました');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Diary $diary)
    {
         // 認可チェック（任意）: 自分の日記か確認したい場合
    // if ($diary->user_id !== auth()->id()) {
    //     abort(403);
    // }

    $diary->forceDelete();

    return to_route('diary.index')->with('success', '日記を削除しました');
    }
}
