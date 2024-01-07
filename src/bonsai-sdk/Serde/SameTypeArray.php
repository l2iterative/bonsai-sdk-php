<?php
namespace L2Iterative\BonsaiSDK\Serde;

use L2Iterative\BonsaiSDK\Exception;

/**
 * A class that represents Vec<T> in Rust
 */
class SameTypeArray implements SerializeInterface
{

    /**
     * The array representing the vector
     *
     * @var array
     */
    public array $var;


    /**
     * A constructor that takes in an array and checks its validity
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
            $var_values = array_values($var);
            if ($var_values !== $var) {
                throw new Exception('array cannot have keys');
            }

            foreach ($var as $value) {
                if (is_object($value) === false || $value instanceof SerializeInterface === false) {
                    throw new Exception('array must contain serializable elements');
                }
            }

            $len                 = count($var);
            $type_of_first_value = get_class($var[0]);
            for ($i = 1; $i < $len; $i++) {
                if (get_class($var[$i]) !== $type_of_first_value) {
                    throw new Exception('elements in the array must have strictly the same type');
                }
            }

            $this->var = $var;
        }//end if

    }//end __construct()


    /**
     * Serialize the array by first including the length.
     *
     * @param array $buffer A reference to the buffer.
     *
     * @return void
     *
     * @throws Exception Whether a violation appears.
     */
    public function write(array &$buffer): void
    {
        $buffer[] = count($this->var);
        foreach ($this->var as $value) {
            $value->write($buffer);
        }

    }//end write()


}//end class
