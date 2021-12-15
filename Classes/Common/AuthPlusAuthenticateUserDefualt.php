<?php

namespace netpioneer\authplus\Classes\Common;

use App\Models\Admin;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use netpioneer\authplus\Classes\AuthPlusLogics;
use netpioneer\authplus\Classes\Captcha;
use netpioneer\authplus\Classes\Consts;
use netpioneer\authplus\Classes\Execptions\LoginFailedExecption;
use netpioneer\authplus\Classes\Execptions\LoginStatusEnum;
use netpioneer\authplus\Classes\Execptions\TooManyRequestsException;
use netpioneer\authplus\Classes\Interfaces\AuthPlusAuthenticateUser;
use netpioneer\authplus\Classes\Interfaces\TwoFactorAuthenticator;
use netpioneer\authplus\Classes\Traits\WithLiveWireRateLimiting;

class AuthPlusAuthenticateUserDefualt implements AuthPlusAuthenticateUser
{
    use WithLiveWireRateLimiting;
    private $captcha_input = '';
    private $twofactor_input;
    private $isTwoFactor = false;
    public function Authenticate($guard,$username,$password,$remember=false)
    {
        try {
            $this->rateLimit(10);
            if($this->captcha_input !== '' && !session()->has(Consts::TwoFactorKey) && !Captcha::check($this->captcha_input)){
                throw new LoginFailedExecption(LoginStatusEnum::WrongCaptcha);
            }else {
                Auth::shouldUse($guard);
                $loginData = ['username' => $username, 'password' => $password];
                //in this sample username == tellphone
                if (Auth::validate($loginData)) {
                    $two_factorEnabled = config('auth.guards.'.$guard.'.login.two-factor');
                    if($two_factorEnabled){
                        if($this->twofactor_input !== null){
                            if(app(TwoFactorAuthenticator::class)->check($this->twofactor_input)){
                                Auth::attempt($loginData,$remember);
                                self::CheckVerification($guard,Auth::user());
                                $this->clearRateLimiter();
                                return Auth::user();
                            }else{
                                throw new LoginFailedExecption(LoginStatusEnum::TwoFactorIncurrect);
                            }
                        }else {
                            session([Consts::TwoFactorKey=>true]);
                            self::HandleTwoFactor($username);
                            throw new LoginFailedExecption(LoginStatusEnum::TwoFactorRequired);
                        }
                    }else {
                        Auth::attempt($loginData,$remember);
                        self::CheckVerification($guard,Auth::user());
                        $this->clearRateLimiter();
                        return Auth::user();
                    }
                } else {
                    throw new LoginFailedExecption(LoginStatusEnum::LoginIncurrect);
                }
            }
        } catch (TooManyRequestsException $exception) {
            throw new LoginFailedExecption(LoginStatusEnum::TooManyRequests,$exception->secondsUntilAvailable);
        }
    }
    private function CheckVerification($guard,$user){
        if(config("auth.guards.$guard.login.check_verification")) {
            $verifications = config("auth.guards.$guard.signup.verify");
            $ex_data = [];
            if (!empty($verifications)) {
                foreach ($verifications as $v) {
                    if ($user->is_verified_ . $v == 0) {
                        $ex_data[] = $v;
                    }
                }
            }
            if (!empty($ex_data)) {
                Auth::logout();
                session()->regenerateToken();
                throw new LoginFailedExecption(LoginStatusEnum::AccountNotVerified, $ex_data);
            }
        }
    }
    public function HandleTwoFactor($tellphone){
        try{
            $this->rateLimit(10);
            app(TwoFactorAuthenticator::class)->genAuth($tellphone);
        }catch (TooManyRequestsException $exception) {
            throw new LoginFailedExecption(LoginStatusEnum::TooManyRequests,$exception->secondsUntilAvailable);
        }
    }
    public function captcha($input){
        $this->captcha_input = $input;
        return $this;
    }
    public function twofactor($input){
        $this->twofactor_input = $input;
        return $this;
    }
}
