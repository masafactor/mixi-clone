<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nickname',
    'nickname_visibility',
    'bio',
    'bio_visibility',
    'gender',
    'gender_visibility',
    'birthdate',
    'birthdate_visibility',
    'location',
    'location_visibility',
    'profile_image_path',
    'prefecture_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
