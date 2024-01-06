<?php

namespace L2Iterative\BonsaiSDK\Responses;

use L2Iterative\BonsaiSDK\Utils\Deserialize;

/**
 * A response for querying the status of a STARK-to-SNARK session
 */
class SnarkStatusRes implements Deserialize
{

    /**
     * A single word describing the current status
     *
     * @var string
     */
    public string $status;

    /**
     * The Groth16 proof
     *
     * @var SnarkReceipt|null
     */
    public ?SnarkReceipt $output;

    /**
     * The error message
     *
     * @var string|null
     */
    public ?string $error_msg;


    /**
     * A constructor for the response for the status of a STARK-to-SNARK session
     *
     * @param string            $status    The status of the STARK-to-SNARK session.
     * @param SnarkReceipt|null $output    The Groth16 proof.
     * @param string|null       $error_msg The error message.
     */
    public function __construct(string $status, ?SnarkReceipt $output, ?string $error_msg)
    {
        $this->status    = $status;
        $this->output    = $output;
        $this->error_msg = $error_msg;

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
        return new self(
            $arr['status'],
            isset($arr['output']) === true ? SnarkReceipt::from_json($arr['output']) : null,
            $arr['error_msg'] ?? null
        );

    }//end from_json()


    /**
     * A method to indicate the serialization preference (object as an array).
     *
     * @return array The array structure of the object.
     */
    public function jsonSerialize(): array
    {
        return [
            'status'    => $this->status,
            'output'    => $this->output?->jsonSerialize(),
            'error_msg' => $this->error_msg,
        ];

    }//end jsonSerialize()


}//end class
