<?php
declare(strict_types=1);

namespace L2Iterative\BonsaiSDK\Tests;

require_once __DIR__ . '/Constants.php';

use donatj\MockWebServer\MockWebServer;
use donatj\MockWebServer\Response;
use L2Iterative\BonsaiSDK\Client;
use L2Iterative\BonsaiSDK\Exception;
use L2Iterative\BonsaiSDK\Responses\CreateSessRes;
use L2Iterative\BonsaiSDK\Responses\SnarkStatusRes;
use L2Iterative\BonsaiSDK\SnarkId;
use L2Iterative\MockWebServerExt\ComplexResponse;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

final class SnarkTest extends TestCase
{


    /**
     * @throws Exception
     */
    public function test_snark_create()
    {
        $server = new MockWebServer();
        $server->start();

        $request_uuid  = (string) Uuid::uuid4();
        $response_uuid = (string) Uuid::uuid4();

        $response = new ComplexResponse();
        $response
            ->when_method_is('POST')
            ->when_header_is("x-api-key", TEST_KEY)
            ->when_header_is("x-risc0-version", TEST_VERSION)
            ->when_header_is('content-type', 'application/json')
            ->when_query_param_is('POST', 'session_id', $request_uuid)
            ->then(new Response(
                json_encode(new CreateSessRes($response_uuid)),
                ['content-type' => 'application/json'],
                200
            ));

        $server->setResponseOfPath(
            '/snark/create',
            $response
        );

        $client = new Client("{$server->getServerRoot()}", TEST_KEY, TEST_VERSION);
        $res    = $client->create_snark($request_uuid);
        $this->assertEquals($res->uuid, $response_uuid);

    }//end test_snark_create()


    /**
     * @throws Exception
     */
    public function test_snark_status()
    {
        $server = new MockWebServer();
        $server->start();

        $uuid = (string) Uuid::uuid4();

        $response = new ComplexResponse();
        $response
            ->when_method_is("GET")
            ->when_header_is("x-api-key", TEST_KEY)
            ->when_header_is("x-risc0-version", TEST_VERSION)
            ->then(new Response(
                json_encode(new SnarkStatusRes('RUNNING', null, null)),
                ['content-type' => 'application/json'],
                200
            ));

        $server->setResponseOfPath(
            "/snark/status/{$uuid}",
            $response
        );

        $client   = new Client("{$server->getServerRoot()}", TEST_KEY, TEST_VERSION);
        $snark_id = new SnarkId($uuid);

        $res = $snark_id->status($client);
        $this->assertEquals('RUNNING', $res->status);
        $this->assertEquals(null, $res->output);

    }//end test_snark_status()


}//end class
