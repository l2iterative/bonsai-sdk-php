<?php

namespace L2Iterative\BonsaiSDK\Serde;

use L2Iterative\BonsaiSDK\Exception;

/**
 * A class representing a Rust u8
 */
class U8 implements SerializeInterface
{

    /**
     * The integer value that hosts the u8
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
        if ($val < 0 || $val > 255) {
            throw new Exception(sprintf('u8 out of range: %d', $val));
        } else {
            $this->val = $val;
        }

    }//end __construct()


    /**
     * Serialize the u8 by converting it to u32 first
     *
     * @param array $buffer A reference to the buffer.
     *
     * @return void
     *
     * @throws Exception Whether a violation appears.
     */
    public function write(array &$buffer): void
    {
        $obj = new U32($this->val);
        $obj->write($buffer);

    }//end write()


}//end class
