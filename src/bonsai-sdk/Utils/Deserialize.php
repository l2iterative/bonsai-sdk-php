<?php

namespace L2Iterative\BonsaiSDK\Utils;

interface Deserialize extends \JsonSerializable
{


    /**
     * A method to deserialize an object in JSON.
     *
     * @param string $json_string The JSON string.
     *
     * @return self The object.
     */
    public static function from_json(string $json_string): self;


}//end interface
