<?php

namespace L2Iterative\BonsaiSDK\Error;

use Exception;

class InternalServerException extends Exception
{

    public string $error_msg;


    public function __construct($error_msg)
    {
        $this->error_msg = $error_msg;
        parent::__construct();

    }//end __construct()


    public function __toString(): string
    {
        return sprintf('server error %s', $this->error_msg);

    }//end __toString()


}//end class
