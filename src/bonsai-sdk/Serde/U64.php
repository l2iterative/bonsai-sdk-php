<?php

namespace L2Iterative\BonsaiSDK\Serde;

use L2Iterative\BonsaiSDK\Exception;

/**
 * A class representing a Rust u64
 */
class U64 implements SerializeInterface
{

    /**
     * The integer value that hosts the u64
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
        $this->val = $val;

    }//end __construct()


    /**
     * Serialize the u64 by splitting it into two u32
     *
     * @param array $buffer A reference to the buffer.
     *
     * @return void
     */
    public function write(array &$buffer): void
    {
        $buffer[] = ($this->val & 0xFFFFFFFF);
        $buffer[] = (($this->val >> 32) & 0xFFFFFFFF);

    }//end write()


}//end class
