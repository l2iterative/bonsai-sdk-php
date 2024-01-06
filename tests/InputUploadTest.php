<?php
declare(strict_types=1);

namespace L2Iterative\BonsaiSDK\Tests;

use donatj\MockWebServer\MockWebServer;
use donatj\MockWebServer\Response;
use donatj\MockWebServer\ResponseByMethod;
use L2Iterative\BonsaiSDK\Client;
use L2Iterative\BonsaiSDK\Exception;
use L2Iterative\BonsaiSDK\Responses\UploadRes;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

final class InputUploadTest extends TestCase
{


    /**
     * @throws Exception
     */
    public function test_input_upload()
    {
        $server = new MockWebServer();
        $server->start();

        $input_uuid = (string) Uuid::uuid4();

        $put_url = $server->setResponseOfPath(
            sprintf('/upload/%s', $input_uuid),
            new ResponseByMethod(
                [ResponseByMethod::METHOD_PUT => new Response('', [], 200)]
            )
        );

        $server->setResponseOfPath(
            '/inputs/upload',
            new ResponseByMethod(
                [
                    ResponseByMethod::METHOD_GET => new Response(
                        json_encode(new UploadRes($put_url, $input_uuid)),
                        ['content-type' => 'application/json'],
                        200
                    ),
                ]
            )
        );

        $client = new Client("{$server->getServerRoot()}", TEST_KEY, TEST_VERSION);
        $res    = $client->upload_input('');
        $this->assertEquals($res, $input_uuid);

    }//end test_input_upload()


}//end class
