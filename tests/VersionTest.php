<?php

declare(strict_types=1);

namespace L2Iterative\BonsaiSDK\Tests;

require_once __DIR__ . '/Constants.php';

use donatj\MockWebServer\MockWebServer;
use donatj\MockWebServer\Response;
use L2Iterative\BonsaiSDK\Client;
use L2Iterative\BonsaiSDK\Exception;
use L2Iterative\BonsaiSDK\Responses\VersionInfo;
use L2Iterative\MockWebServerExt\ComplexResponse;
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

        $response = new ComplexResponse();
        $response
            ->when_method_is("GET")
            ->when_header_is("x-api-key", TEST_KEY)
            ->when_header_is("x-risc0-version", TEST_VERSION)
            ->then(new Response(
                json_encode(new VersionInfo([TEST_VERSION])),
                ['content-type' => 'application/json'],
                200
            ));

        $server->setResponseOfPath(
            '/version',
            $response
        );

        $client = new Client("{$server->getServerRoot()}", TEST_KEY, TEST_VERSION);
        $res    = $client->version();
        $this->assertEquals(TEST_VERSION, $res->risc0_zkvm[0]);

    }//end test_version()


}//end class
