<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Block extends Model
{
    public function blockedUser()
    {
        return $this->belongsTo(User::class, 'blocked_user_id');
    }
}
