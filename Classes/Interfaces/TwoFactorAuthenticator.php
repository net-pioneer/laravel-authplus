<?php

namespace netpioneer\authplus\Classes\Interfaces;

interface TwoFactorAuthenticator
{
    public function genAuth($tellphone);

    public function check($userInput);
}
