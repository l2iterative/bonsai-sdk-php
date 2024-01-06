<?php

namespace L2Iterative\BonsaiSDK\Responses;

use L2Iterative\BonsaiSDK\Utils\Deserialize;

/**
 * A response used to upload the image.
 */
class ImgUploadRes implements Deserialize
{

    /**
     * The URL to send the PUT request
     *
     * @var string
     */
    public string $url;


    /**
     * A constructor for the image upload response
     *
     * @param string $url The URL to send the PUT request.
     */
    public function __construct(string $url)
    {
        $this->url = $url;

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
        return new self($obj->url);

    }//end from_json()


    /**
     * A method to indicate the serialization preference (object as an array).
     *
     * @return array The array structure of the object.
     */
    public function jsonSerialize(): array
    {
        return [
            'url' => $this->url,
        ];

    }//end jsonSerialize()


}//end class
