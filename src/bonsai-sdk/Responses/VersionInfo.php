<?php

namespace L2Iterative\BonsaiSDK\Responses;

use L2Iterative\BonsaiSDK\Utils\Deserialize;

class VersionInfo implements Deserialize
{

    public array $risc0_zkvm;


    function __construct(array $risc0_zkvm)
    {
        $this->risc0_zkvm = $risc0_zkvm;

    }//end __construct()


    public static function from_json(string $json_string): self
    {
        $arr = json_decode($json_string);
        return new self($arr->risc0_zkvm);

    }//end from_json()


    public function jsonSerialize(): mixed
    {
        return [
            'risc0_zkvm' => $this->risc0_zkvm,
        ];

    }//end jsonSerialize()


}//end class
