<?php

namespace L2Iterative\BonsaiSDK\Requests;

use L2Iterative\BonsaiSDK\Utils\Deserialize;

/**
 * A class representing a request to convert a STARK proof to a SNARK proof
 */
class SnarkReq implements Deserialize
{

    /**
     * The STARK session to be created a SNARK for
     *
     * @var string
     */
    public string $session_id;


    /**
     * A constructor for a request to convert a STARK proof to a SNARK proof
     *
     * @param string $session_id The STARK session to be created a SNARK for.
     */
    public function __construct(string $session_id)
    {
        $this->session_id = $session_id;

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
        $obj = json_decode($json_string);
        return new self($obj->session_id);

    }//end from_json()


    /**
     * A method to indicate the serialization preference (object as an array).
     *
     * @return array The array structure of the object.
     */
    public function jsonSerialize(): array
    {
        return [
            'session_id' => $this->session_id,
        ];

    }//end jsonSerialize()


}//end class
