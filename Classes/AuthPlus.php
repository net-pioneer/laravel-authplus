<?php

namespace netpioneer\authplus\Classes;

use Illuminate\Http\Request;
use netpioneer\authplus\Classes\Interfaces\AuthPlusAuthenticateUser;
use netpioneer\authplus\Classes\Interfaces\AuthPlusRedirectAuthenticated;
use netpioneer\authplus\Classes\Interfaces\TwoFactorAuthenticator;

class AuthPlus
{
    public static function AuthenticateMethod($class){
        app()->singleton(AuthPlusAuthenticateUser::class, $class);
    }
    public static function RedirectAuthenticated($class){
        app()->singleton(AuthPlusRedirectAuthenticated::class, $class);
    }
    public static function TwoFactorAuthenticator($class){
        app()->singleton(TwoFactorAuthenticator::class, $class);
    }
}
