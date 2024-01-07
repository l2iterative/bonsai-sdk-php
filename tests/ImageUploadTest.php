<?php

declare(strict_types=1);

namespace L2Iterative\BonsaiSDK\Tests;

require_once __DIR__ . '/Constants.php';

use donatj\MockWebServer\MockWebServer;
use donatj\MockWebServer\Response;
use donatj\MockWebServer\ResponseByMethod;
use L2Iterative\BonsaiSDK\Client;
use L2Iterative\BonsaiSDK\Exception;
use L2Iterative\BonsaiSDK\Responses\ImgUploadRes;
use L2Iterative\MockWebServerExt\ComplexResponse;
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

        $upload_response = new ComplexResponse();
        $upload_response
            ->when_method_is("GET")
            ->when_header_is("x-api-key", TEST_KEY)
            ->when_header_is("x-risc0-version", TEST_VERSION)
            ->then(new Response(
                json_encode(new ImgUploadRes($put_url)),
                ['content-type' => 'application/json'],
                200
            ));

        $server->setResponseOfPath(
            sprintf('/images/upload/%s', TEST_ID),
            $upload_response
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

        $upload_response = new ComplexResponse();
        $upload_response
            ->when_method_is("GET")
            ->when_header_is("x-api-key", TEST_KEY)
            ->when_header_is("x-risc0-version", TEST_VERSION)
            ->then(new Response(
                '',
                [],
                204
            ));

        $server->setResponseOfPath(
            sprintf('/images/upload/%s', TEST_ID),
            $upload_response
        );

        $client = new Client($server->getServerRoot(), TEST_KEY, TEST_VERSION);
        $res    = $client->upload_img(TEST_ID, 'A');
        $this->assertTrue($res);

    }//end test_image_upload_dup()


}//end class
