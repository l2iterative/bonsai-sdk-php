<?php

namespace L2Iterative\BonsaiSDK\Responses;

use L2Iterative\BonsaiSDK\Utils\Deserialize;

class ImgUploadRes implements Deserialize
{

    public string $url;


    function __construct(string $url)
    {
        $this->url = $url;

    }//end __construct()


    public static function from_json(string $json_string): self
    {
        $obj = json_decode($json_string);
        return new self($obj->url);

    }//end from_json()


    public function jsonSerialize(): array
    {
        return [
            'url' => $this->url,
        ];

    }//end jsonSerialize()


}//end class
