<?php

namespace L2Iterative\BonsaiSDK\Error;

use Exception;
use GuzzleHttp\Exception\GuzzleException;

class HttpErr extends Exception
{

    public GuzzleException $exception;


    public function __construct($exception)
    {
        $this->exception = $exception;
        parent::__construct();

    }//end __construct()


    public function __toString(): string
    {
        return sprintf('HTTP error from GuzzleHttp library: %s', $this->exception);

    }//end __toString()


}//end class
