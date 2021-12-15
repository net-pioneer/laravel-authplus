<?php

namespace netpioneer\authplus\Classes\Common;

use netpioneer\authplus\Classes\Interfaces\TwoFactorAuthenticator;

class AuthPlusTwoFactorSmsAuthenticator implements TwoFactorAuthenticator
{

    public function genAuth($tellphone)
    {
        // TODO: Implement genAuth() method.
        $code = 123;
        session(['_ap_sms'=>$code]);
        //send sms here
        // sendsms::to(tell,$code);
    }

    public function check($userInput)
    {
        // TODO: Implement check() method.
        if(session()->get('_ap_sms') == $userInput){
            return true;
        }else{
            return false;
        }
    }
}
