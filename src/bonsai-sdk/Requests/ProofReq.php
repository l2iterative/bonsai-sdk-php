<?php

namespace L2Iterative\BonsaiSDK\Requests;

use L2Iterative\BonsaiSDK\Utils\Deserialize;

/**
 * A class representing a request to generate a STARK proof
 */
class ProofReq implements Deserialize
{

    /**
     * The image ID to be used to generate a proof for
     *
     * @var string
     */
    public string $img;

    /**
     * The ID corresponding to the uploaded input data.
     *
     * @var string
     */
    public string $input;

    /**
     * A list of assumptions (receipt IDs) that this STARK session would require.
     *
     * @var array
     */
    public array $assumptions;


    /**
     * A constructor for a request to generate a STARK proof.
     *
     * @param string $img         The ID corresponding to the uploaded input data.
     * @param string $input       The ID corresponding to the uploaded input data.
     * @param array  $assumptions A list of assumptions (receipt IDs) that this STARK session would require.
     */
    public function __construct(string $img, string $input, array $assumptions)
    {
        $this->img         = $img;
        $this->input       = $input;
        $this->assumptions = $assumptions;

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
        return new self($obj->img, $obj->input, $obj->assumptions);

    }//end from_json()


    /**
     * A method to indicate the serialization preference (object as an array).
     *
     * @return array The array structure of the object.
     */
    public function jsonSerialize(): array
    {
        return [
            'img'         => $this->img,
            'input'       => $this->input,
            'assumptions' => $this->assumptions,
        ];

    }//end jsonSerialize()


}//end class
