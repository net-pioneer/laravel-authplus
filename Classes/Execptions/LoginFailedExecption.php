<?php

namespace netpioneer\authplus\Classes\Execptions;

use Exception;
use Throwable;

class LoginFailedExecption extends Exception
{
    private $status;
    private $data;

    /**
     * @return int
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * @return mixed|string
     */
    public function getData()
    {
        return $this->data;
    }
    public function __construct($status,$data="")
    {
        $this->status = $status;
        $this->data = $data;
    }

}
