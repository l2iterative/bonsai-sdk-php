<?php

namespace L2Iterative\BonsaiSDK;

use GuzzleHttp\Exception\GuzzleException;
use L2Iterative\BonsaiSDK\Responses\ImageExistsOpt;
use L2Iterative\BonsaiSDK\Responses\UploadRes;

/**
 * The Bonsai API PHP client.
 */
class Client
{

    /**
     * The Bonsai API server's URL, starting with "http://" or "https://"
     *
     * @var string
     */
    public string $url;

    /**
     * The HTTP client from GuzzleHttp
     *
     * @var \GuzzleHttp\Client
     */
    public \GuzzleHttp\Client $client;


    /**
     * The constructor of the Client
     *
     * @param string $url           The Bonsai API server's URL.
     * @param string $key           The Bonsai API key.
     * @param string $risc0_version The current risc0-zkvm version.
     */
    public function __construct(string $url, string $key, string $risc0_version)
    {
        $this->url    = rtrim($url, '/');
        $this->client = new \GuzzleHttp\Client(
            [
                'headers' => [
                    'x-api-key'       => $key,
                    'x-risc0-version' => $risc0_version,
                ],
            ]
        );

    }//end __construct()


    /**
     * Retrieve a URL from the Bonsai API server to upload files
     *
     * @param string $route Specify the type of the upload path required.
     *
     * @return UploadRes The upload response containing the URL to make a PUT request.
     *
     * @throws Exception Exception if GuzzleHttp throws an exception or when the status code is not 2xx.
     */
    public function get_upload_url(string $route): Responses\UploadRes
    {
        try {
            $res = $this->client->get(
                sprintf(
                    '%s/%s/upload',
                    $this->url,
                    $route
                )
            );

            $status_code = $res->getStatusCode();

            if ($status_code < 200 || $status_code > 300) {
                throw new Exception($res->getBody());
            }

            return Responses\UploadRes::from_json($res->getBody());
        } catch (GuzzleException $e) {
            throw new Exception($e);
        }

    }//end get_upload_url()


    /**
     * Retrieve a URL from the Bonsai API server to upload the image if the image does not exist
     *
     * @param string $image_id The image ID.
     *
     * @return ImageExistsOpt A response containing the URL to upload if the image does not exist
     *
     * @throws Exception Exception if GuzzleHttp throws an exception or when the status code is not 2xx.
     */
    public function get_image_upload_url(string $image_id): Responses\ImageExistsOpt
    {
        try {
            $res = $this->client->get(
                sprintf(
                    '%s/images/upload/%s',
                    $this->url,
                    $image_id,
                )
            );

            $status_code = $res->getStatusCode();

            if ($status_code < 200 || $status_code > 300) {
                throw new Exception($res->getBody());
            }

            if ($status_code === 204) {
                return Responses\ImageExistsOpt::Exists();
            } else {
                $imgUploadRes = Responses\ImgUploadRes::from_json($res->getBody());
                return Responses\ImageExistsOpt::New($imgUploadRes);
            }
        } catch (GuzzleException $e) {
            throw new Exception($e);
        }//end try

    }//end get_image_upload_url()


    /**
     * A helper method to send PUT request
     *
     * @param string $url  The URL to send the PUT request.
     * @param string $body The binary data to be uploaded.
     *
     * @return void
     *
     * @throws Exception Exception if GuzzleHttp throws an exception or when the status code is not 2xx.
     */
    public function put_data(string $url, string $body): void
    {
        try {
            $res = $this->client->put(
                $url,
                ['body' => $body]
            );

            $status_code = $res->getStatusCode();
            if ($status_code < 200 || $status_code > 300) {
                throw new Exception($res->getBody());
            }
        } catch (GuzzleException $e) {
            throw new Exception($e);
        }

    }//end put_data()


    /**
     * A method to upload the image
     *
     * @param string $image_id The computed image ID of the uploaded image.
     * @param string $buf      The image data.
     *
     * @return boolean Whether the image already exists on the server.
     *
     * @throws Exception Exception if GuzzleHttp throws an exception or when the status code is not 2xx.
     */
    public function upload_img(string $image_id, string $buf): bool
    {
        $res_or_exists = $this->get_image_upload_url($image_id);
        if ($res_or_exists->exists === true) {
            return true;
        } else {
            $this->put_data($res_or_exists->imgUploadRes->url, $buf);
            return false;
        }

    }//end upload_img()


    /**
     * A method to upload the image from a path
     *
     * @param string $image_id The computed image ID of the uploaded image.
     * @param string $path     The path to the image.
     *
     * @return boolean Whether the image already exists on the server.
     *
     * @throws Exception Exception if GuzzleHttp throws an exception or when the status code is not 2xx.
     */
    public function upload_img_file(string $image_id, string $path): bool
    {
        $res_or_exists = $this->get_image_upload_url($image_id);
        if ($res_or_exists->exists === true) {
            return true;
        } else {
            $data = file_get_contents($path);
            $this->put_data($res_or_exists->imgUploadRes->url, $data);
            return false;
        }

    }//end upload_img_file()


    /**
     * A method to upload the input data
     *
     * @param string $buf The input data.
     *
     * @return string The ID of the uploaded input data.
     *
     * @throws Exception Exception if GuzzleHttp throws an exception or when the status code is not 2xx.
     */
    public function upload_input(string $buf): string
    {
        $upload_data = $this->get_upload_url('inputs');
        $this->put_data($upload_data->url, $buf);
        return $upload_data->uuid;

    }//end upload_input()


    /**
     * A method to upload input data from file
     *
     * @param string $path The path to the input data.
     *
     * @return string The ID of the uploaded input data.
     *
     * @throws Exception Exception if GuzzleHttp throws an exception or when the status code is not 2xx.
     */
    public function upload_input_file(string $path): string
    {
        $upload_data = $this->get_upload_url('inputs');
        $data        = file_get_contents($path);
        $this->put_data($upload_data->url, $data);
        return $upload_data->uuid;

    }//end upload_input_file()


    /**
     * A method to upload the receipt data
     *
     * @param string $buf The receipt data.
     *
     * @return string The ID of the uploaded receipt.
     *
     * @throws Exception Exception if GuzzleHttp throws an exception or when the status code is not 2xx.
     */
    public function upload_receipt(string $buf): string
    {
        $upload_data = $this->get_upload_url('receipts');
        $this->put_data($upload_data->url, $buf);
        return $upload_data->uuid;

    }//end upload_receipt()


    /**
     * A method to upload receipt data from a file
     *
     * @param string $path The path to the receipt data.
     *
     * @return string The ID of the uploaded receipt.
     *
     * @throws Exception Exception if GuzzleHttp throws an exception or when the status code is not 2xx.
     */
    public function upload_receipt_file(string $path): string
    {
        $upload_data = $this->get_upload_url('receipts');
        $data        = file_get_contents($path);
        $this->put_data($upload_data->url, $data);
        return $upload_data->uuid;

    }//end upload_receipt_file()


    /**
     * A method to create a STARK session
     *
     * @param string $img_id      The image ID to be used to generate a proof for.
     * @param string $input_id    The ID corresponding to the uploaded input data.
     * @param array  $assumptions A list of assumptions (receipt IDs) that this STARK session would require.
     *
     * @return string The STARK session ID.
     *
     * @throws Exception Exception if GuzzleHttp throws an exception or when the status code is not 2xx.
     */
    public function create_session(string $img_id, string $input_id, array $assumptions): string
    {
        try {
            $res = $this->client->post(
                sprintf(
                    '%s/sessions/create',
                    $this->url,
                ),
                [
                    'json' => [
                        'img'         => $img_id,
                        'input'       => $input_id,
                        'assumptions' => $assumptions,
                    ],
                ]
            );

            $status_code = $res->getStatusCode();
            if ($status_code < 200 || $status_code > 300) {
                throw new Exception($res->getBody());
            }

            $res = Responses\CreateSessRes::from_json($res->getBody());
            return $res->uuid;
        } catch (GuzzleException $e) {
            throw new Exception($e);
        }//end try

    }//end create_session()


    /**
     * A helper method to download data from a URL
     *
     * @param string $url The URL to download the data.
     *
     * @return string The binary data of the HTTP response.
     *
     * @throws Exception Exception if GuzzleHttp throws an exception or when the status code is not 2xx.
     */
    public function download(string $url): string
    {
        try {
            $res = $this->client->get($url);

            $status_code = $res->getStatusCode();
            if ($status_code < 200 || $status_code > 300) {
                throw new Exception(
                    sprintf('cannot download the file: http status code %d', $status_code)
                );
            }

            return $res->getBody();
        } catch (GuzzleException $e) {
            throw new Exception($e);
        }

    }//end download()


    /**
     * A method to create a STARK-to-SNARK session
     *
     * @param string $session_id The STARK session to be created a SNARK for.
     *
     * @return SnarkId The STARK-to-SNARK session ID.
     *
     * @throws Exception Exception if GuzzleHttp throws an exception or when the status code is not 2xx.
     */
    public function create_snark(string $session_id): SnarkId
    {
        try {
            $res = $this->client->post(
                sprintf(
                    '%s/snark/create',
                    $this->url,
                ),
                [
                    'json' => ['session_id' => $session_id],
                ]
            );

            $status_code = $res->getStatusCode();
            if ($status_code < 200 || $status_code > 300) {
                throw new Exception($res->getBody());
            }

            $res = Responses\CreateSessRes::from_json($res->getBody());
            return new SnarkId($res->uuid);
        } catch (GuzzleException $e) {
            throw new Exception($e);
        }//end try

    }//end create_snark()


    /**
     * A method to obtain the risc0-zkvm version of the Bonsai API server
     *
     * @return Responses\VersionInfo A list of supported risc0-zkvm versions.
     *
     * @throws Exception Exception if GuzzleHttp throws an exception or when the status code is not 2xx.
     */
    public function version(): Responses\VersionInfo
    {
        try {
            $res = $this->client->get(
                sprintf(
                    '%s/version',
                    $this->url,
                )
            );

            $status_code = $res->getStatusCode();
            if ($status_code < 200 || $status_code > 300) {
                throw new Exception($res->getBody());
            }

            return Responses\VersionInfo::from_json($res->getBody());
        } catch (GuzzleException $e) {
            throw new Exception($e);
        }

    }//end version()


    /**
     * A method to obtain the current quota usage
     *
     * @return Responses\Quotas Information about the current quota usage.
     *
     * @throws Exception Exception if GuzzleHttp throws an exception or when the status code is not 2xx.
     */
    public function quotas(): Responses\Quotas
    {
        try {
            $res = $this->client->get(
                sprintf('%s/user/quotas', $this->url)
            );

            $status_code = $res->getStatusCode();
            if ($status_code < 200 || $status_code > 300) {
                throw new Exception($res->getBody());
            }

            return Responses\Quotas::from_json($res->getBody());
        } catch (GuzzleException $e) {
            throw new Exception($e);
        }

    }//end quotas()


}//end class
