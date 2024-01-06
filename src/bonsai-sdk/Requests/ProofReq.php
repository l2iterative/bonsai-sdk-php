<?php

namespace L2Iterative\BonsaiSDK\Requests;

use L2Iterative\BonsaiSDK\Utils\Deserialize;

class ProofReq implements Deserialize
{

    public string $img;

    public string $input;

    public array $assumptions;


    function __construct($img, $input, $assumptions)
    {
        $this->img         = $img;
        $this->input       = $input;
        $this->assumptions = $assumptions;

    }//end __construct()


    public static function from_json(string $json_string): self
    {
        $obj = json_decode($json_string);
        return new self($obj->img, $obj->input, $obj->assumptions);

    }//end from_json()


    public function jsonSerialize(): array
    {
        return [
            'img'         => $this->img,
            'input'       => $this->input,
            'assumptions' => $this->assumptions,
        ];

    }//end jsonSerialize()


}//end class
