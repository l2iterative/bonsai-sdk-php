<?php

namespace L2Iterative\BonsaiSDK\Responses;

use L2Iterative\BonsaiSDK\Utils\Deserialize;

class SessionStatusRes implements Deserialize
{

    public string $status;

    public ?string $receipt_url;

    public ?string $error_msg;

    public ?string $state;


    function __construct(string $status, ?string $receipt_url, ?string $error_msg, ?string $state)
    {
        $this->status      = $status;
        $this->receipt_url = $receipt_url;
        $this->error_msg   = $error_msg;
        $this->state       = $state;

    }//end __construct()


    public static function from_json(string $json_string): self
    {
        $arr = json_decode($json_string, true);
        return new self($arr['status'], $arr['receipt_url'] ?? null, $arr['error_msg'] ?? null, $arr['state'] ?? null);

    }//end from_json()


    public function jsonSerialize(): array
    {
        return [
            'status'      => $this->status,
            'receipt_url' => $this->receipt_url,
            'error_msg'   => $this->error_msg,
            'state'       => $this->state,
        ];

    }//end jsonSerialize()


}//end class
