<?php

namespace L2Iterative\BonsaiSDK\Responses;

use L2Iterative\BonsaiSDK\Utils\Deserialize;

/**
 * A SNARK receipt, which is a Groth16 proof
 */
class SnarkReceipt implements Deserialize
{

    /**
     * The group element A in a Groth16 proof
     *
     * @var array
     */
    public array $snark_a;

    /**
     * The group element B in a Groth16 proof
     *
     * @var array
     */
    public array $snark_b;

    /**
     * The group element C in a Groth16 proof
     *
     * @var array
     */
    public array $snark_c;

    /**
     * The public input
     *
     * @var array
     */
    public array $snark_public;

    /**
     * The post-state digest
     *
     * @var array
     */
    public array $post_state_digest;

    /**
     * The journal of the zkvm
     *
     * @var array
     */
    public array $journal;


    /**
     * A constructor of a SNARK receipt
     *
     * @param array $snark_a           The group element A in a Groth16 proof.
     * @param array $snark_b           The group element B in a Groth16 proof.
     * @param array $snark_c           The group element C in a Groth16 proof.
     * @param array $snark_public      The public input.
     * @param array $post_state_digest The post-state digest.
     * @param array $journal           The journal of the zkvm.
     */
    public function __construct(
        array $snark_a,
        array $snark_b,
        array $snark_c,
        array $snark_public,
        array $post_state_digest,
        array $journal
    ) {
        $this->snark_a      = $snark_a;
        $this->snark_b      = $snark_b;
        $this->snark_c      = $snark_c;
        $this->snark_public = $snark_public;

        $this->post_state_digest = $post_state_digest;
        $this->journal           = $journal;

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
        return new self(
            $obj->snark->a,
            $obj->snark->b,
            $obj->snark->c,
            $obj->snark->public,
            $obj->post_state_digest,
            $obj->journal,
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
            'snark'             => [
                'a'      => $this->snark_a,
                'b'      => $this->snark_b,
                'c'      => $this->snark_c,
                'public' => $this->snark_public,
            ],
            'post_state_digest' => $this->post_state_digest,
            'journal'           => $this->journal,
        ];

    }//end jsonSerialize()


}//end class
