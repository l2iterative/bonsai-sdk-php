<?php

namespace L2Iterative\BonsaiSDK;

/**
 * A wrapped exception class.
 */
class Exception extends \Exception
{

    /**
     * The human-readable error message
     *
     * @var string
     */
    public string $error_msg;


    /**
     * A constructor of the exception
     *
     * @param string $error_msg The error message.
     */
    public function __construct(string $error_msg)
    {
        $this->error_msg = $error_msg;
        parent::__construct();

    }//end __construct()


    /**
     * The conversion method to be a string.
     *
     * @return string The displayed error message.
     */
    public function __toString(): string
    {
        return sprintf('server error %s', $this->error_msg);

    }//end __toString()


}//end class
