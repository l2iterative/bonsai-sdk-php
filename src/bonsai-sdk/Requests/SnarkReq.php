<?php

namespace L2Iterative\BonsaiSDK\Requests;

use L2Iterative\BonsaiSDK\Utils\Deserialize;

class SnarkReq implements Deserialize
{

    public string $session_id;


    function __construct(string $session_id)
    {
        $this->session_id = $session_id;

    }//end __construct()


    public static function from_json(string $json_string): self
    {
        $obj = json_decode($json_string);
        return new self($obj->session_id);

    }//end from_json()


    public function jsonSerialize(): array
    {
        return [
            'session_id' => $this->session_id,
        ];

    }//end jsonSerialize()


}//end class
