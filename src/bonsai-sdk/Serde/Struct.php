<?php
namespace L2Iterative\BonsaiSDK\Serde;

use L2Iterative\BonsaiSDK\Exception;

/**
 * A class that represents a struct in Rust
 */
class Struct implements SerializeInterface
{

    /**
     * An array where each element is serializable
     *
     * @var array
     */
    public array $var;


    /**
     * A constructor that takes an array and checks its validity
     *
     * @param array $var The input array.
     *
     * @throws Exception Whether a violation appears.
     */
    public function __construct(array $var)
    {
        if (count($var) === 0) {
            $this->var = [];
        } else {
            foreach ($var as $value) {
                if (is_object($value) === false || $value instanceof SerializeInterface === false) {
                    throw new Exception('struct must contain serializable elements');
                }
            }

            $this->var = $var;
        }

    }//end __construct()


    /**
     * Serialize a struct
     *
     * @param array $buffer A reference to the buffer.
     *
     * @return void
     *
     * @throws Exception Whether a violation appears.
     */
    public function write(array &$buffer): void
    {
        foreach ($this->var as $value) {
            $value->write($buffer);
        }

    }//end write()


}//end class
