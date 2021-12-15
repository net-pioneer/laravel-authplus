<?php

namespace netpioneer\authplus\Classes\Common;

use Illuminate\Support\Facades\Auth;
use netpioneer\authplus\Classes\AuthPlusLogics;
use netpioneer\authplus\Classes\Interfaces\AuthPlusRedirectAuthenticated;

class AuthPlusRedirectAuthenticatedDefault implements AuthPlusRedirectAuthenticated
{

    public function RedirectHandle($guards)
    {
        // TODO: Implement RedirectHandle() method.
        $guards = empty($guards) ? [null] : $guards;
        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                return redirect(AuthPlusLogics::getHomePage());
            }
        }
        return null;
    }
}
