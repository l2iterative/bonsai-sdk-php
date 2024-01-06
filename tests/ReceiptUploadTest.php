<?php
declare(strict_types=1);

namespace L2Iterative\BonsaiSDK\Tests;

use donatj\MockWebServer\MockWebServer;
use donatj\MockWebServer\Response;
use donatj\MockWebServer\ResponseByMethod;
use L2Iterative\BonsaiSDK\Client;
use L2Iterative\BonsaiSDK\Error\InternalServerException;
use L2Iterative\BonsaiSDK\Responses\UploadRes;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

const TEST_KEY     = 'TESTKEY';
const TEST_ID      = '0x5891b5b522d5df086d0ff0b110fbd9d21bb4fc7163af34d08286a2e846f6be03';
const TEST_VERSION = '0.1.0';

final class ReceiptUploadTest extends TestCase
{


    /**
     * @throws InternalServerException
     */
    public function test_receipt_upload()
    {
        $server = new MockWebServer();
        $server->start();

        $receipt_uuid = (string) Uuid::uuid4();

        $put_url = $server->setResponseOfPath(
            sprintf('/upload/%s', $receipt_uuid),
            new ResponseByMethod(
                [ResponseByMethod::METHOD_PUT => new Response('', [], 200)]
            )
        );

        $server->setResponseOfPath(
            '/receipts/upload',
            new ResponseByMethod(
                [
                    ResponseByMethod::METHOD_GET => new Response(
                        json_encode(new UploadRes($put_url, $receipt_uuid)),
                        ['content-type' => 'application/json'],
                        200
                    ),
                ]
            )
        );

        $client = new Client("{$server->getServerRoot()}", TEST_KEY, TEST_VERSION);
        $res    = $client->upload_receipt('');
        $this->assertEquals($res, $receipt_uuid);

    }//end test_receipt_upload()


}//end class
