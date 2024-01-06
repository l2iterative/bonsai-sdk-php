<?php
declare(strict_types=1);

namespace L2Iterative\BonsaiSDK\Tests;

use donatj\MockWebServer\MockWebServer;
use donatj\MockWebServer\Response;
use donatj\MockWebServer\ResponseByMethod;
use L2Iterative\BonsaiSDK\Client;
use L2Iterative\BonsaiSDK\Exception;
use L2Iterative\BonsaiSDK\Responses\CreateSessRes;
use L2Iterative\BonsaiSDK\Responses\SessionStatusRes;
use L2Iterative\BonsaiSDK\SessionId;
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

        $server->setResponseOfPath(
            '/sessions/create',
            new ResponseByMethod(
                [
                    ResponseByMethod::METHOD_POST => new Response(
                        json_encode(new CreateSessRes($uuid)),
                        ['content-type' => 'application/json'],
                        200
                    ),
                ]
            )
        );

        $client = new Client("{$server->getServerRoot()}", TEST_KEY, TEST_VERSION);
        $res    = $client->create_session(TEST_ID, $uuid, []);
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

        $server->setResponseOfPath(
            "/sessions/status/{$uuid}",
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

        $server->setResponseOfPath(
            "/sessions/logs/{$uuid}",
            new ResponseByMethod(
                [
                    ResponseByMethod::METHOD_GET => new Response(
                        "Hello\nWorld",
                        ['content-type' => 'text/plain'],
                        200
                    ),
                ]
            )
        );

        $client     = new Client("{$server->getServerRoot()}", TEST_KEY, TEST_VERSION);
        $session_id = new SessionId($uuid);

        $res = $session_id->logs($client);
        $this->assertEquals("Hello\nWorld", $res);

    }//end test_session_logs()


}//end class
