<?php
namespace L2Iterative\BonsaiSDK\Serde;

use L2Iterative\BonsaiSDK\Exception;

/**
 * A class that represents a Rust bool
 */
class Boolean implements SerializeInterface
{

    /**
     * The boolean value
     *
     * @var boolean
     */
    public bool $val;


    /**
     * A constructor that takes a bool
     *
     * @param boolean $val The input boolean value.
     */
    public function __construct(bool $val)
    {
        $this->val = $val;

    }//end __construct()


    /**
     * Serialize a bool by converting it to u8
     *
     * @param array $buffer A reference to the buffer.
     *
     * @return void
     *
     * @throws Exception Whether a violation appears.
     */
    public function write(array &$buffer): void
    {
        $obj = new U8((int) $this->val);
        $obj->write($buffer);

    }//end write()


}//end class
