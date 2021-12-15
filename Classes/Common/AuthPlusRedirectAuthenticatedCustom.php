<?php

namespace netpioneer\authplus\Classes\Common;

use Illuminate\Support\Facades\Auth;
use netpioneer\authplus\Classes\AuthPlusLogics;
use netpioneer\authplus\Classes\Interfaces\AuthPlusRedirectAuthenticated;

class AuthPlusRedirectAuthenticatedCustom implements AuthPlusRedirectAuthenticated
{

    public function RedirectHandle($guards)
    {
        // TODO: Implement RedirectHandle() method.

        return null;
    }
}
