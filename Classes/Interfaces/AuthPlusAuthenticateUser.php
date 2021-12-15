<?php

namespace netpioneer\authplus\Classes\Interfaces;

use Illuminate\Http\Request;

interface AuthPlusAuthenticateUser
{

    public function Authenticate($guard,$username,$password,$remember=false);
}
