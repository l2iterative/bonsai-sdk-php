<?php

namespace L2Iterative\BonsaiSDK\Responses;

use L2Iterative\BonsaiSDK\Utils\Deserialize;

/**
 *  A response for querying the status of a STARK session
 */
class SessionStatusRes implements Deserialize
{

    /**
     * A single word describing the current status
     *
     * @var string
     */
    public string $status;

    /**
     * URL to download the receipt
     *
     * @var string|null
     */
    public ?string $receipt_url;

    /**
     * The error message
     *
     * @var string|null
     */
    public ?string $error_msg;

    /**
     * Last reported active state that the execution was in
     *
     * @var string|null
     */
    public ?string $state;


    /**
     * A constructor for the response for the status of a STARK session
     *
     * @param string      $status      A single word describing the current status.
     * @param string|null $receipt_url URL to download the receipt.
     * @param string|null $error_msg   The error message.
     * @param string|null $state       Last reported active state that the execution was in.
     */
    public function __construct(string $status, ?string $receipt_url, ?string $error_msg, ?string $state)
    {
        $this->status      = $status;
        $this->receipt_url = $receipt_url;
        $this->error_msg   = $error_msg;
        $this->state       = $state;

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
        $arr = json_decode($json_string, true);
        return new self($arr['status'], $arr['receipt_url'] ?? null, $arr['error_msg'] ?? null, $arr['state'] ?? null);

    }//end from_json()


    /**
     * A method to indicate the serialization preference (object as an array).
     *
     * @return array The array structure of the object.
     */
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
