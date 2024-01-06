<?php

namespace L2Iterative\BonsaiSDK\Responses;

use L2Iterative\BonsaiSDK\Utils\Deserialize;

class UploadRes implements Deserialize
{

    public string $url;

    public string $uuid;


    function __construct(string $url, string $uuid)
    {
        $this->url  = $url;
        $this->uuid = $uuid;

    }//end __construct()


    public static function from_json(string $json_string): self
    {
        $obj = json_decode($json_string);
        return new self($obj->url, $obj->uuid);

    }//end from_json()


    public function jsonSerialize(): array
    {
        return [
            'url'  => $this->url,
            'uuid' => $this->uuid,
        ];

    }//end jsonSerialize()


}//end class
