<?php
declare(strict_types=1);

namespace L2Iterative\BonsaiSDK\Tests;

require_once __DIR__ . '/Constants.php';

use donatj\MockWebServer\MockWebServer;
use donatj\MockWebServer\Response;
use L2Iterative\BonsaiSDK\Client;
use L2Iterative\BonsaiSDK\Exception;
use L2Iterative\BonsaiSDK\Responses\CreateSessRes;
use L2Iterative\BonsaiSDK\Responses\SessionStatusRes;
use L2Iterative\BonsaiSDK\SessionId;
use L2Iterative\MockWebServerExt\ComplexResponse;
use L2Iterative\MockWebServerExt\Matcher\RegexMatcher;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;


final class SessionTest extends TestCase
{


    /**
     * @throws Exception
     */
    public function test_session_create()
    {
        $server = new MockWebServer();
        $server->start();

        $uuid = (string) Uuid::uuid4();

        $response = new ComplexResponse();
        $response
            ->when_method_is("POST")
            ->when_header_is("x-api-key", TEST_KEY)
            ->when_header_is("x-risc0-version", TEST_VERSION)
            ->when_header_is("content-type", "application/json")
            ->when_query_param_is('POST', 'img', TEST_ID)
            ->when_query_param_is('POST', 'input', $uuid)
            ->when_query_param_exists('POST', 'assumptions')
            ->when_query_param_is('POST', 'assumptions', json_encode(["a"]))
            ->then(new Response(
                json_encode(new CreateSessRes($uuid)),
                ['content-type' => 'application/json'],
                200
            ));

        $server->setResponseOfPath(
            '/sessions/create',
            $response
        );

        $client = new Client("{$server->getServerRoot()}", TEST_KEY, TEST_VERSION);
        $res    = $client->create_session(TEST_ID, $uuid, ["a"]);
        $this->assertEquals($res, $uuid);

    }//end test_session_create()


    /**
     * @throws Exception
     */
    public function test_session_status()
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
                json_encode(new SessionStatusRes('RUNNING', null, null, null)),
                ['content-type' => 'application/json'],
                200
            ));

        $server->setResponseOfPath(
            "/sessions/status/{$uuid}",
            $response
        );

        $client     = new Client("{$server->getServerRoot()}", TEST_KEY, TEST_VERSION);
        $session_id = new SessionId($uuid);

        $res = $session_id->status($client);
        $this->assertEquals('RUNNING', $res->status);
        $this->assertEquals(null, $res->receipt_url);

    }//end test_session_status()


    /**
     * @throws Exception
     */
    public function test_session_logs()
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
                "Hello\nWorld",
                ['content-type' => 'text/plain'],
                200
            ));

        $server->setResponseOfPath(
            "/sessions/logs/{$uuid}",
            $response
        );

        $client     = new Client("{$server->getServerRoot()}", TEST_KEY, TEST_VERSION);
        $session_id = new SessionId($uuid);

        $res = $session_id->logs($client);
        $this->assertEquals("Hello\nWorld", $res);

    }//end test_session_logs()


}//end class
