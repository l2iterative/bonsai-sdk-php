<?php

declare(strict_types=1);

namespace L2Iterative\BonsaiSDK\Tests;

require_once __DIR__ . '/Constants.php';

use donatj\MockWebServer\MockWebServer;
use donatj\MockWebServer\Response;
use L2Iterative\BonsaiSDK\Client;
use L2Iterative\BonsaiSDK\Exception;
use L2Iterative\BonsaiSDK\Responses\Quotas;
use L2Iterative\MockWebServerExt\ComplexResponse;
use PHPUnit\Framework\TestCase;

final class QuotasTest extends TestCase
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
                json_encode(new Quotas(500, 2, 10, 100000, 1000000)),
                ['content-type' => 'application/json'],
                200
            ));

        $server->setResponseOfPath(
            '/user/quotas',
            $response
        );

        $client = new Client("{$server->getServerRoot()}", TEST_KEY, TEST_VERSION);
        $res    = $client->quotas();
        $this->assertEquals(500, $res->exec_cycle_limit);
        $this->assertEquals(2, $res->max_parallelism);
        $this->assertEquals(10, $res->concurrent_proofs);
        $this->assertEquals(100000, $res->cycle_budget);
        $this->assertEquals(1000000, $res->cycle_usage);

    }//end test_version()


}//end class
