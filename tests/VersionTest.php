<?php

declare(strict_types=1);

namespace L2Iterative\BonsaiSDK\Tests;

use donatj\MockWebServer\MockWebServer;
use donatj\MockWebServer\Response;
use donatj\MockWebServer\ResponseByMethod;
use L2Iterative\BonsaiSDK\Client;
use L2Iterative\BonsaiSDK\Error\InternalServerException;
use L2Iterative\BonsaiSDK\Responses\VersionInfo;
use PHPUnit\Framework\TestCase;

const TEST_KEY     = 'TESTKEY';
const TEST_ID      = '0x5891b5b522d5df086d0ff0b110fbd9d21bb4fc7163af34d08286a2e846f6be03';
const TEST_VERSION = '0.1.0';

final class VersionTest extends TestCase
{


    /**
     * @throws InternalServerException
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
