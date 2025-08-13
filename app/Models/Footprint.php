<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Footprint extends Model
{
    // app/Models/Footprint.php
public function viewer()
{
    return $this->belongsTo(User::class, 'viewer_id');
}
public function visitedUser()
{
    return $this->belongsTo(User::class, 'visited_user_id');
}

}
