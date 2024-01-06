<?php

namespace L2Iterative\BonsaiSDK\Responses;

use L2Iterative\BonsaiSDK\Utils\Deserialize;

class CreateSessRes implements Deserialize
{

    public string $uuid;


    function __construct(string $uuid)
    {
        $this->uuid = $uuid;

    }//end __construct()


    public static function from_json(string $json_string): self
    {
        $obj = json_decode($json_string);
        return new self($obj->uuid);

    }//end from_json()


    public function jsonSerialize(): array
    {
        return [
            'uuid' => $this->uuid,
        ];

    }//end jsonSerialize()


}//end class
