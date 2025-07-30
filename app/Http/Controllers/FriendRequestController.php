<?php

namespace App\Http\Controllers;

use App\Models\FriendUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class FriendRequestController extends Controller
{
     public function index()
    {
        $requests = FriendUser::with('sender')
            ->where('receiver_id', Auth::id())
            ->where('status', 'pending')
            ->get();

        return Inertia::render('FriendRequests/Index', [
            'requests' => $requests
        ]);
    }
}
