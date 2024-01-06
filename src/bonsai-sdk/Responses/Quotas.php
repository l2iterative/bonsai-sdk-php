<?php

namespace L2Iterative\BonsaiSDK\Responses;

use L2Iterative\BonsaiSDK\Utils\Deserialize;

class Quotas implements Deserialize
{

    public int $exec_cycle_limit;

    public int $max_parallelism;

    public int $concurrent_proofs;

    public int $cycle_budget;

    public int $cycle_usage;


    function __construct(
        int $exec_cycle_limit,
        int $max_parallelism,
        int $concurrent_proofs,
        int $cycle_budget,
        int $cycle_usage,
    ) {
        $this->exec_cycle_limit  = $exec_cycle_limit;
        $this->max_parallelism   = $max_parallelism;
        $this->concurrent_proofs = $concurrent_proofs;
        $this->cycle_budget      = $cycle_budget;
        $this->cycle_usage       = $cycle_usage;

    }//end __construct()


    public static function from_json(string $json_string): self
    {
        $obj = json_decode($json_string);
        return new self(
            $obj->exec_cycle_limit,
            $obj->max_parallelism,
            $obj->concurrent_proofs,
            $obj->cycle_budget,
            $obj->cycle_usage
        );

    }//end from_json()


    public function jsonSerialize(): array
    {
        return [
            'exec_cycle_limit'  => $this->exec_cycle_limit,
            'max_parallelism'   => $this->max_parallelism,
            'concurrent_proofs' => $this->concurrent_proofs,
            'cycle_budget'      => $this->cycle_budget,
            'cycle_usage'       => $this->cycle_usage,
        ];

    }//end jsonSerialize()


}//end class
