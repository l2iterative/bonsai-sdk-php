<?php
namespace L2Iterative\BonsaiSDK\Serde;

/**
 * A class that represents Option::None
 */
class None implements SerializeInterface
{


    /**
     * Serialize None as a zero
     *
     * @param array $buffer A reference to the buffer.
     *
     * @return void
     */
    public function write(array &$buffer): void
    {
        $buffer[] = 0;

    }//end write()


}//end class
