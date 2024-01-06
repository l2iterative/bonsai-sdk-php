<?php

declare(strict_types=1);

namespace L2Iterative\BonsaiSDK\Tests;

use donatj\MockWebServer\MockWebServer;
use donatj\MockWebServer\Response;
use donatj\MockWebServer\ResponseByMethod;
use L2Iterative\BonsaiSDK\Client;
use L2Iterative\BonsaiSDK\Error\InternalServerException;
use L2Iterative\BonsaiSDK\Responses\ImgUploadRes;
use PHPUnit\Framework\TestCase;

const TEST_KEY     = 'TESTKEY';
const TEST_ID      = '0x5891b5b522d5df086d0ff0b110fbd9d21bb4fc7163af34d08286a2e846f6be03';
const TEST_VERSION = '0.1.0';

final class ImageUploadTest extends TestCase
{


    /**
     * @throws InternalServerException
     */
    public function test_image_upload()
    {
        $server = new MockWebServer();
        $server->start();

        $put_url = $server->setResponseOfPath(
            sprintf('/upload/%s', TEST_ID),
            new ResponseByMethod(
                [ResponseByMethod::METHOD_PUT => new Response('', [], 200)]
            )
        );

        $server->setResponseOfPath(
            sprintf('/images/upload/%s', TEST_ID),
            new ResponseByMethod(
                [
                    ResponseByMethod::METHOD_GET => new Response(
                        json_encode(new ImgUploadRes($put_url)),
                        ['content-type' => 'application/json'],
                        200
                    ),
                ]
            )
        );

        $client = new Client("{$server->getServerRoot()}", TEST_KEY, TEST_VERSION);
        $res    = $client->upload_img(TEST_ID, '');
        $this->assertFalse($res);

    }//end test_image_upload()


    /**
     * @throws InternalServerException
     */
    public function test_image_upload_dup()
    {
        $server = new MockWebServer();
        $server->start();

        $server->setResponseOfPath(
            sprintf('/upload/%s', TEST_ID),
            new ResponseByMethod(
                [ResponseByMethod::METHOD_PUT => new Response('', [], 200)]
            )
        );

        $server->setResponseOfPath(
            sprintf('/images/upload/%s', TEST_ID),
            new ResponseByMethod(
                [
                    ResponseByMethod::METHOD_GET => new Response(
                        '',
                        [],
                        204
                    ),
                ]
            )
        );

        $client = new Client($server->getServerRoot(), TEST_KEY, TEST_VERSION);
        $res    = $client->upload_img(TEST_ID, 'A');
        $this->assertTrue($res);

    }//end test_image_upload_dup()


}//end class
