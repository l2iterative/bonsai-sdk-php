<?php
namespace L2Iterative\BonsaiSDK\Serde;

use L2Iterative\BonsaiSDK\Exception;

/**
 * A class representing a Rust i64
 */
class I64 implements SerializeInterface
{

    /**
     * The integer value that hosts the i64
     *
     * @var integer
     */
    public int $val;


    /**
     * A constructor that takes an integer and checks its validity
     *
     * @param integer $val The input integer value.
     */
    public function __construct(int $val)
    {
        $this->val = $val;

    }//end __construct()


    /**
     * Serialize the i64 by converting it to u64 first
     *
     * @param array $buffer A reference to the buffer.
     *
     * @return void
     */
    public function write(array &$buffer): void
    {
        $obj = new U64($this->val);
        $obj->write($buffer);

    }//end write()


}//end class
