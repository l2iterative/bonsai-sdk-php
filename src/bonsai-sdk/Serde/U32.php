<?php

namespace L2Iterative\BonsaiSDK\Serde;

use L2Iterative\BonsaiSDK\Exception;

/**
 * A class representing a Rust u32
 */
class U32 implements SerializeInterface
{

    /**
     * The integer value that hosts the u32
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
        if ($val < 0 || $val > 4294967295) {
            throw new Exception(sprintf('u32 out of range: %d', $val));
        } else {
            $this->val = $val;
        }

    }//end __construct()


    /**
     * Serialize the u32 by writing it into the buffer immediately
     *
     * @param array $buffer A reference to the buffer.
     *
     * @return void
     */
    public function write(array &$buffer): void
    {
        $buffer[] = $this->val;

    }//end write()


}//end class
