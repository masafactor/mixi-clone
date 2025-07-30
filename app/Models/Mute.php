<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mute extends Model
{
    public function mutedUser()
    {
        return $this->belongsTo(User::class, 'muted_user_id');
    }
}
