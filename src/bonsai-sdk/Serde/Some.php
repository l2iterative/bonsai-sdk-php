<?php
namespace L2Iterative\BonsaiSDK\Serde;

use L2Iterative\BonsaiSDK\Exception;

/**
 * A class representing Option::Some::<T>
 */
class Some implements SerializeInterface
{

    /**
     * The child serializable element
     *
     * @var SerializeInterface
     */
    public SerializeInterface $var;


    /**
     * A constructor that takes in a serializable element
     *
     * @param SerializeInterface $var The serializable element.
     */
    public function __construct(SerializeInterface $var)
    {
        $this->var = $var;

    }//end __construct()


    /**
     * Serialize Some by appending a one and serializing the child next
     *
     * @param array $buffer A reference to the buffer.
     *
     * @return void
     */
    public function write(array &$buffer): void
    {
        $buffer[] = 1;
        $this->var->write($buffer);

    }//end write()


}//end class
