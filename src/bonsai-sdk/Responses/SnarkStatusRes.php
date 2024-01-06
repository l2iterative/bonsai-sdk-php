<?php

namespace L2Iterative\BonsaiSDK\Responses;

use L2Iterative\BonsaiSDK\Utils\Deserialize;

class SnarkStatusRes implements Deserialize
{

    public string $status;

    public ?string $output;

    public ?string $error_msg;


    function __construct(string $status, ?string $output, ?string $error_msg)
    {
        $this->status    = $status;
        $this->output    = $output;
        $this->error_msg = $error_msg;

    }//end __construct()


    public static function from_json(string $json_string): self
    {
        $arr = json_decode($json_string, true);
        return new self($arr['status'], $arr['output'] ?? null, $arr['error_msg'] ?? null);

    }//end from_json()


    public function jsonSerialize(): array
    {
        return [
            'status'    => $this->status,
            'output'    => $this->output,
            'error_msg' => $this->error_msg,
        ];

    }//end jsonSerialize()


}//end class
