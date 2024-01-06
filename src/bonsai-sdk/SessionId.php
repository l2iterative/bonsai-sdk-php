<?php

namespace L2Iterative\BonsaiSDK;

use GuzzleHttp\Exception\GuzzleException;
use L2Iterative\BonsaiSDK\Responses\SessionStatusRes;

/**
 * A class representing a STARK session
 */
class SessionId
{

    /**
     * The ID of the STARK session
     *
     * @var string
     */
    public string $uuid;


    /**
     * A constructor of the STARK session ID
     *
     * @param string $uuid The ID of the STARK session.
     */
    public function __construct(string $uuid)
    {
        $this->uuid = $uuid;

    }//end __construct()


    /**
     * A method to query for the status of the STARK session
     *
     * @param Client $client The Bonsai API client.
     *
     * @return SessionStatusRes A response containing the status and the output of the STARK session.
     *
     * @throws Exception Exception if GuzzleHttp throws an exception or when the status code is not 2xx.
     */
    public function status(Client $client): SessionStatusRes
    {
        try {
            $res = $client->client->get(
                sprintf(
                    '%s/sessions/status/%s',
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

            return SessionStatusRes::from_json($res->getBody());
        } catch (GuzzleException $e) {
            throw new Exception($e);
        }

    }//end status()


    /**
     * A method to retrieve the log information of a STARK session
     *
     * @param Client $client The Bonsai API client.
     *
     * @return string The logs.
     *
     * @throws Exception Exception if GuzzleHttp throws an exception or when the status code is not 2xx.
     */
    public function logs(Client $client): string
    {
        try {
            $res = $client->client->get(
                sprintf(
                    '%s/sessions/logs/%s',
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

            return $res->getBody();
        } catch (GuzzleException $e) {
            throw new Exception($e);
        }

    }//end logs()


}//end class
