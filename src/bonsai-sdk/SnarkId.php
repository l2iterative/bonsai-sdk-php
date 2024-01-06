<?php

namespace L2Iterative\BonsaiSDK;

use GuzzleHttp\Exception\GuzzleException;
use L2Iterative\BonsaiSDK\Responses\SnarkStatusRes;

/**
 * A class representing a STARK-to-SNARK session
 */
class SnarkId
{

    /**
     * The ID of the STARK-to-SNARK session
     *
     * @var string
     */
    public string $uuid;


    /**
     * A constructor of the STARK-to-SNARK session ID
     *
     * @param string $uuid The ID of the STARK-to-SNARK session.
     */
    public function __construct(string $uuid)
    {
        $this->uuid = $uuid;

    }//end __construct()


    /**
     * A method to query for the status of a STARK-to-SNARK session
     *
     * @param Client $client The Bonsai API client.
     *
     * @return SnarkStatusRes A response containing the status and the output of the STARK-to-SNARK session.
     *
     * @throws Exception Exception if GuzzleHttp throws an exception or when the status code is not 2xx.
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
                throw new Exception(
                    sprintf('cannot download the file: http status code %d', $status_code)
                );
            }

            return Responses\SnarkStatusRes::from_json($res->getBody());
        } catch (GuzzleException $e) {
            throw new Exception($e);
        }

    }//end status()


}//end class
