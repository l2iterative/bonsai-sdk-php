<?php

namespace L2Iterative\BonsaiSDK\Responses;

/**
 * A class representing an enum on the image upload URL
 */
class ImageExistsOpt
{

    /**
     * The response used to upload the image
     *
     * @var ImgUploadRes|null
     */
    public ?ImgUploadRes $imgUploadRes;

    /**
     * A boolean indicating whether the image already exists on the server
     *
     * @var boolean
     */
    public bool $exists;


    /**
     * A constructor of the image upload enum
     *
     * @param ImgUploadRes|null $imgUploadRes The response used to upload the image.
     * @param boolean           $exists       A boolean indicating whether the image already exists on the server.
     */
    public function __construct(?ImgUploadRes $imgUploadRes, bool $exists)
    {
        $this->imgUploadRes = $imgUploadRes;
        $this->exists       = $exists;

    }//end __construct()


    /**
     * A shorthand constructor indicating that the image already exists on the server
     *
     * @return self
     */
    public static function Exists(): self
    {
        return new self(null, true);

    }//end Exists()


    /**
     * A shorthand constructor representing the image upload URL
     *
     * @param ImgUploadRes $imgUploadRes The response used to upload the image.
     *
     * @return self
     */
    public static function New(ImgUploadRes $imgUploadRes): self
    {
        return new self($imgUploadRes, false);

    }//end New()


}//end class
