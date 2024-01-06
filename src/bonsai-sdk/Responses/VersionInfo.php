<?php

namespace L2Iterative\BonsaiSDK\Responses;

use L2Iterative\BonsaiSDK\Utils\Deserialize;

/**
 * A response consisting of the supported risc0-zkvm versions
 */
class VersionInfo implements Deserialize
{

    /**
     * A list of supported risc0-zkvm versions
     *
     * @var array
     */
    public array $risc0_zkvm;


    /**
     * A constructor of the response on supported risc0-zkvm versions
     *
     * @param array $risc0_zkvm A list of supported risc0-zkvm versions.
     */
    public function __construct(array $risc0_zkvm)
    {
        $this->risc0_zkvm = $risc0_zkvm;

    }//end __construct()


    /**
     * A method to deserialize an object in JSON.
     *
     * @param string $json_string The JSON string.
     *
     * @return self The object.
     */
    public static function from_json(string $json_string): self
    {
        $arr = json_decode($json_string);
        return new self($arr->risc0_zkvm);

    }//end from_json()


    /**
     * A method to indicate the serialization preference (object as an array).
     *
     * @return array The array structure of the object.
     */
    public function jsonSerialize(): mixed
    {
        return [
            'risc0_zkvm' => $this->risc0_zkvm,
        ];

    }//end jsonSerialize()


}//end class
