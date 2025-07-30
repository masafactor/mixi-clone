<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Diary extends Model
{
    protected $fillable = [
    'title',
    'body',
    'user_id', // ←必要なフィールドをすべて書く
];
}
