<?php

declare(strict_types=1);

namespace L2Iterative\BonsaiSDK\Tests;

use donatj\MockWebServer\MockWebServer;
use donatj\MockWebServer\Response;
use donatj\MockWebServer\ResponseByMethod;
use L2Iterative\BonsaiSDK\Client;
use L2Iterative\BonsaiSDK\Exception;
use L2Iterative\BonsaiSDK\Responses\VersionInfo;
use PHPUnit\Framework\TestCase;

final class VersionTest extends TestCase
{


    /**
     * @throws Exception
     */
    public function test_version()
    {
        $server = new MockWebServer();
        $server->start();

        $server->setResponseOfPath(
            '/version',
            new ResponseByMethod(
                [
                    ResponseByMethod::METHOD_GET => new Response(
                        json_encode(new VersionInfo([TEST_VERSION])),
                        ['content-type' => 'application/json'],
                        200
                    ),
                ]
            )
        );

        $client = new Client("{$server->getServerRoot()}", TEST_KEY, TEST_VERSION);
        $res    = $client->version();
        $this->assertEquals(TEST_VERSION, $res->risc0_zkvm[0]);

    }//end test_version()


}//end class
