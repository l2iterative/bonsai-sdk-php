<?php

namespace L2Iterative\BonsaiSDK\Utils;

use JsonSerializable;

interface Deserialize extends JsonSerializable
{


    public static function from_json(string $json_string): self;


}//end interface
