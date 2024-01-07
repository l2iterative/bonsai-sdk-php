<?php
namespace L2Iterative\BonsaiSDK\Serde;

use L2Iterative\BonsaiSDK\Exception;

/**
 * A class representing a Rust i8
 */
class I8 implements SerializeInterface
{

    /**
     * The integer value that hosts the i8
     *
     * @var integer
     */
    public int $val;


    /**
     * A constructor that takes an integer and checks its validity
     *
     * @param integer $val The input integer value.
     *
     * @throws Exception Whether a violation appears.
     */
    public function __construct(int $val)
    {
        if ($val < -128 || $val > 127) {
            throw new Exception(sprintf('i8 out of range: %d', $val));
        } else {
            $this->val = $val;
        }

    }//end __construct()


    /**
     * Serialize the i8 by converting it to i32 first
     *
     * @param array $buffer A reference to the buffer.
     *
     * @return void
     *
     * @throws Exception Whether a violation appears.
     */
    public function write(array &$buffer): void
    {
        $obj = new I32($this->val);
        $obj->write($buffer);

    }//end write()


}//end class
