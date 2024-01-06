<?php

namespace L2Iterative\BonsaiSDK\Responses;

use L2Iterative\BonsaiSDK\Utils\Deserialize;

class SnarkReceipt implements Deserialize
{

    public array $snark_a;

    public array $snark_b;

    public array $snark_c;

    public array $snark_public;

    public array $post_state_digest;

    public array $journal;


    function __construct(
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
