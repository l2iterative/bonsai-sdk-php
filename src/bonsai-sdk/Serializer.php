<?php
namespace L2Iterative\BonsaiSDK;

use L2Iterative\BonsaiSDK\Serde\SerializeInterface;

/**
 * The PHP serializer for RISC Zero
 */
class Serializer
{

    /**
     * The data buffer
     *
     * @var array
     */
    public array $data;


    /**
     * A constructor that initializes an empty buffer
     */
    public function __construct()
    {
        $this->data = [];

    }//end __construct()


    /**
     * Serialize a serializable element
     *
     * @param SerializeInterface $var The input serializable element.
     *
     * @return void
     */
    public function serialize(SerializeInterface $var): void
    {
        $var->write($this->data);

    }//end serialize()


    /**
     * Return the data in the buffer
     *
     * @return array
     */
    public function output(): array
    {
        return $this->data;

    }//end output()


}//end class
