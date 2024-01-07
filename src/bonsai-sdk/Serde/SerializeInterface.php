<?php
namespace L2Iterative\BonsaiSDK\Serde;

use L2Iterative\BonsaiSDK\Exception;

/**
 * The interface for serialization
 */
interface SerializeInterface
{


    /**
     * Serialize the data
     *
     * @param array $buffer A reference to the buffer.
     *
     * @return void
     */
    public function write(array &$buffer): void;


}//end interface
