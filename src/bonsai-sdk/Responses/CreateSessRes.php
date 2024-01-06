<?php

namespace L2Iterative\BonsaiSDK\Responses;

use L2Iterative\BonsaiSDK\Utils\Deserialize;

/**
 * A response for creating a STARK session
 */
class CreateSessRes implements Deserialize
{

    /**
     * The ID of the STARK session
     *
     * @var string
     */
    public string $uuid;


    /**
     * A constructor of a response for creating a STARK session
     *
     * @param string $uuid The ID of the STARK session.
     */
    public function __construct(string $uuid)
    {
        $this->uuid = $uuid;

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
        return new self($obj->uuid);

    }//end from_json()


    /**
     * A method to indicate the serialization preference (object as an array).
     *
     * @return array The array structure of the object.
     */
    public function jsonSerialize(): array
    {
        return [
            'uuid' => $this->uuid,
        ];

    }//end jsonSerialize()


}//end class
