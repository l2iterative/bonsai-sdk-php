<?php
namespace L2Iterative\BonsaiSDK\Serde;

use L2Iterative\BonsaiSDK\Exception;

/**
 * A class representing a Rust i32
 */
class I32 implements SerializeInterface
{

    /**
     * The integer value that hosts the i32
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
        if ($val < -2147483648 || $val > 2147483647) {
            throw new Exception(sprintf('i32 out of range: %d', $val));
        } else {
            $this->val = $val;
        }

    }//end __construct()


    /**
     * Serialize the i32 by converting it to u32 first
     *
     * @param array $buffer A reference to the buffer.
     *
     * @return void
     *
     * @throws Exception Whether a violation appears.
     */
    public function write(array &$buffer): void
    {
        if ($this->val < 0) {
            $obj = new U32($this->val + 4294967296);
        } else {
            $obj = new U32($this->val);
        }

        $obj->write($buffer);

    }//end write()


}//end class
