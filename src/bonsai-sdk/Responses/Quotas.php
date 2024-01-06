<?php

namespace L2Iterative\BonsaiSDK\Responses;

use L2Iterative\BonsaiSDK\Utils\Deserialize;

/**
 * A response consisting of the user's quotas usage
 */
class Quotas implements Deserialize
{

    /**
     * Executor cycle limit, in millions of cycles
     *
     * @var integer
     */
    public int $exec_cycle_limit;

    /**
     * Max parallel proving units
     *
     * @var integer
     */
    public int $max_parallelism;

    /**
     * Max concurrent proofs
     *
     * @var integer
     */
    public int $concurrent_proofs;

    /**
     * Current cycle budget remaining
     *
     * @var integer
     */
    public int $cycle_budget;

    /**
     * Lifetime cycles used
     *
     * @var integer
     */
    public int $cycle_usage;


    /**
     * A constructor for the quotas usage report
     *
     * @param integer $exec_cycle_limit  Executor cycle limit, in millions of cycles.
     * @param integer $max_parallelism   Max parallel proving units.
     * @param integer $concurrent_proofs Max concurrent proofs.
     * @param integer $cycle_budget      Current cycle budget remaining.
     * @param integer $cycle_usage       Lifetime cycles used.
     */
    public function __construct(
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
            $obj->exec_cycle_limit,
            $obj->max_parallelism,
            $obj->concurrent_proofs,
            $obj->cycle_budget,
            $obj->cycle_usage
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
            'exec_cycle_limit'  => $this->exec_cycle_limit,
            'max_parallelism'   => $this->max_parallelism,
            'concurrent_proofs' => $this->concurrent_proofs,
            'cycle_budget'      => $this->cycle_budget,
            'cycle_usage'       => $this->cycle_usage,
        ];

    }//end jsonSerialize()


}//end class
