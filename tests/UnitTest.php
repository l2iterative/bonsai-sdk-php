<?php
declare(strict_types=1);

namespace L2Iterative\BonsaiSDK\Tests;

use L2Iterative\BonsaiSDK\Client;
use PHPUnit\Framework\TestCase;

final class UnitTest extends TestCase
{


    public function test_client_from_parts()
    {
        $url    = 'http://127.0.0.1/stage';
        $apikey = TEST_KEY;

        $client = new Client($url, $apikey, TEST_VERSION);
        $this->assertEquals($client->url, $url);

    }//end test_client_from_parts()


    public function test_client_test_slash_strip()
    {
        $url    = 'http://127.0.0.1/';
        $apikey = TEST_KEY;

        $client = new Client($url, $apikey, TEST_VERSION);
        $this->assertEquals('http://127.0.0.1', $client->url);

    }//end test_client_test_slash_strip()


}//end class
