<?php

namespace L2Iterative\BonsaiSDK\Responses;

use L2Iterative\BonsaiSDK\Utils\Deserialize;

class ImageExistsOpt
{

    public ?ImgUploadRes $imgUploadRes;

    public bool $exists;


    function __construct(?ImgUploadRes $imgUploadRes, bool $exists)
    {
        $this->imgUploadRes = $imgUploadRes;
        $this->exists       = $exists;

    }//end __construct()


    public static function Exists(): self
    {
        return new self(null, true);

    }//end Exists()


    public static function New(ImgUploadRes $imgUploadRes): self
    {
        return new self($imgUploadRes, false);

    }//end New()


}//end class
