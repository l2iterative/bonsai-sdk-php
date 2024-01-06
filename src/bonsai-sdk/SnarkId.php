<?php

namespace L2Iterative\BonsaiSDK;

use GuzzleHttp\Exception\GuzzleException;
use L2Iterative\BonsaiSDK\Error\InternalServerException;

class SnarkId
{

    public string $uuid;


    function __construct(string $uuid)
    {
        $this->uuid = $uuid;

    }//end __construct()


    /**
     * @throws InternalServerException
     */
    public function status(Client $client): Responses\SnarkStatusRes
    {
        try {
            $res = $client->client->get(
                sprintf(
                    '%s/snark/status/%s',
                    $client->url,
                    $this->uuid
                )
            );

            $status_code = $res->getStatusCode();
            if ($status_code < 200 || $status_code > 300) {
                throw new Error\InternalServerException(
                    sprintf('cannot download the file: http status code %d', $status_code)
                );
            }

            return Responses\SnarkStatusRes::from_json($res->getBody());
        } catch (GuzzleException $e) {
            throw new Error\InternalServerException($e);
        }

    }//end status()


}//end class
