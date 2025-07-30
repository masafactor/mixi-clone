<?php

namespace App\Actions\Fortify;

use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;
use Illuminate\Http\Request;
class LoginResponse implements LoginResponseContract
{
    /**
     * Create a new class instance.
     */
   public function toResponse($request)
    {
        $user = $request->user();

        if (!$user->username) {
            return redirect()->route('username.register');
        }

        return redirect()->route('users.show', $user); // or dashboard
    }
}
