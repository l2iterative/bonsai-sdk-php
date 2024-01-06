<?php
declare(strict_types=1);

namespace L2Iterative\BonsaiSDK\Tests;

use donatj\MockWebServer\MockWebServer;
use donatj\MockWebServer\Response;
use donatj\MockWebServer\ResponseByMethod;
use L2Iterative\BonsaiSDK\Client;
use L2Iterative\BonsaiSDK\Error\InternalServerException;
use L2Iterative\BonsaiSDK\Responses\CreateSessRes;
use L2Iterative\BonsaiSDK\Responses\SessionStatusRes;
use L2Iterative\BonsaiSDK\SnarkId;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

const TEST_KEY     = 'TESTKEY';
const TEST_ID      = '0x5891b5b522d5df086d0ff0b110fbd9d21bb4fc7163af34d08286a2e846f6be03';
const TEST_VERSION = '0.1.0';

final class SnarkTest extends TestCase
{


    /**
     * @throws InternalServerException
     */
    public function test_snark_create()
    {
        $server = new MockWebServer();
        $server->start();

        $request_uuid  = (string) Uuid::uuid4();
        $response_uuid = (string) Uuid::uuid4();

        $server->setResponseOfPath(
            '/snark/create',
            new ResponseByMethod(
                [
                    ResponseByMethod::METHOD_POST => new Response(
                        json_encode(new CreateSessRes($response_uuid)),
                        ['content-type' => 'application/json'],
                        200
                    ),
                ]
            )
        );

        $client = new Client("{$server->getServerRoot()}", TEST_KEY, TEST_VERSION);
        $res    = $client->create_snark($request_uuid);
        $this->assertEquals($res->uuid, $response_uuid);

    }//end test_snark_create()


    /**
     * @throws InternalServerException
     */
    public function test_snark_status()
    {
        $server = new MockWebServer();
        $server->start();

        $uuid = (string) Uuid::uuid4();

        $server->setResponseOfPath(
            "/snark/status/{$uuid}",
            new ResponseByMethod(
                [
                    ResponseByMethod::METHOD_GET => new Response(
                        json_encode(new SessionStatusRes('RUNNING', null, null, null)),
                        ['content-type' => 'application/json'],
                        200
                    ),
                ]
            )
        );

        $client   = new Client("{$server->getServerRoot()}", TEST_KEY, TEST_VERSION);
        $snark_id = new SnarkId($uuid);

        $res = $snark_id->status($client);
        $this->assertEquals('RUNNING', $res->status);
        $this->assertEquals(null, $res->output);

    }//end test_snark_status()


}//end class
