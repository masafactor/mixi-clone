<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Footprint extends Model
{
    // 量産時の代入安全性
    protected $fillable = [
        'viewer_id',
        'visited_user_id',
        'visited_on',
    ];

    // 日付カラムの型付け（Carbon）
    protected $casts = [
        'visited_on' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // 一覧で毎回使うなら常時Eager LoadしてもOK（好み）
    // protected $with = ['viewer'];

    /* ---------- Relations ---------- */
    public function viewer()
    {
        return $this->belongsTo(User::class, 'viewer_id');
    }

    public function visitedUser()
    {
        return $this->belongsTo(User::class, 'visited_user_id');
    }

    /* ---------- Scopes（任意） ---------- */
    public function scopeForVisited($query, int $userId)
    {
        return $query->where('visited_user_id', $userId);
    }

    public function scopeLatestVisit($query)
    {
        return $query->orderByDesc('updated_at');
    }

    public function scopeToday($query)
    {
        return $query->whereDate('visited_on', now()->toDateString());
    }

    public function scopeRecentForVisited($q, int $userId, int $limit = 10)
    {
        return $q->forVisited($userId)->latestVisit()->with('viewer')->limit($limit);
    }
}
