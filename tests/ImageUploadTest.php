<?php

declare(strict_types=1);

namespace L2Iterative\BonsaiSDK\Tests;

require __DIR__ . '/Constants.php';

use donatj\MockWebServer\MockWebServer;
use donatj\MockWebServer\Response;
use donatj\MockWebServer\ResponseByMethod;
use L2Iterative\BonsaiSDK\Client;
use L2Iterative\BonsaiSDK\Exception;
use L2Iterative\BonsaiSDK\Responses\ImgUploadRes;
use PHPUnit\Framework\TestCase;

final class ImageUploadTest extends TestCase
{


    /**
     * @throws Exception
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
     * @throws Exception
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
