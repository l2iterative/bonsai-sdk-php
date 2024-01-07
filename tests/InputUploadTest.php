<?php
declare(strict_types=1);

namespace L2Iterative\BonsaiSDK\Tests;

require_once __DIR__ . '/Constants.php';

use donatj\MockWebServer\MockWebServer;
use donatj\MockWebServer\Response;
use donatj\MockWebServer\ResponseByMethod;
use L2Iterative\BonsaiSDK\Client;
use L2Iterative\BonsaiSDK\Exception;
use L2Iterative\BonsaiSDK\Responses\UploadRes;
use L2Iterative\MockWebServerExt\ComplexResponse;
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

        $upload_response = new ComplexResponse();
        $upload_response
            ->when_method_is("GET")
            ->when_header_is("x-api-key", TEST_KEY)
            ->when_header_is("x-risc0-version", TEST_VERSION)
            ->then(new Response(
                json_encode(new UploadRes($put_url, $input_uuid)),
                ['content-type' => 'application/json'],
                200
            ));

        $server->setResponseOfPath(
            '/inputs/upload',
            $upload_response
        );

        $client = new Client("{$server->getServerRoot()}", TEST_KEY, TEST_VERSION);
        $res    = $client->upload_input('');
        $this->assertEquals($res, $input_uuid);

    }//end test_input_upload()


}//end class
