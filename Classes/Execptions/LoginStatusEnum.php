<?php

namespace netpioneer\authplus\Classes\Execptions;

class LoginStatusEnum
{
    const OK=1,LoginIncurrect=2,TooManyRequests=3,WrongCaptcha=4,TwoFactorRequired=5,TwoFactorIncurrect=6,AccountNotVerified=7;
}
