<?php

namespace netpioneer\authplus\Classes;

use Illuminate\Support\Facades\Auth;

class AuthPlusLogics
{

    public static function getGuardName(){
        //Auth::shouldUse('user');
        $g = auth()->guard();
        $sessionName = $g->getName();
        $parts = explode("_", $sessionName);
        unset($parts[count($parts)-1]);
        unset($parts[0]);
        $guardName = implode("_",$parts);
        return $guardName;
    }
    public static function getHomePage(){
        $guard = self::getGuardName();
        $config = config('auth');
        $url = $config['prefix'].'/'.$config['guards'][$guard]['url']."/test";
        return $url;
    }
    public static function uid(){
        return Auth::user()->id;
    }
}
