<?php

namespace L2Iterative\BonsaiSDK;

use GuzzleHttp\Exception\GuzzleException;
use L2Iterative\BonsaiSDK\Error\InternalServerException;

class Client
{

    public string $url;

    public \GuzzleHttp\Client $client;


    function __construct(string $url, string $key, string $risc0_version)
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
     * @throws InternalServerException
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
                throw new InternalServerException($res->getBody());
            }

            return Responses\UploadRes::from_json($res->getBody());
        } catch (GuzzleException $e) {
            throw new InternalServerException($e);
        }

    }//end get_upload_url()


    /**
     * @throws InternalServerException
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
                throw new InternalServerException($res->getBody());
            }

            if ($status_code == 204) {
                return Responses\ImageExistsOpt::Exists();
            } else {
                $imgUploadRes = Responses\ImgUploadRes::from_json($res->getBody());
                return Responses\ImageExistsOpt::New($imgUploadRes);
            }
        } catch (GuzzleException $e) {
            throw new InternalServerException($e);
        }//end try

    }//end get_image_upload_url()


    /**
     * @throws InternalServerException
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
                throw new InternalServerException($res->getBody());
            }
        } catch (GuzzleException $e) {
            throw new InternalServerException($e);
        }

    }//end put_data()


    /**
     * @throws InternalServerException
     */
    public function upload_img(string $image_id, string $buf): bool
    {
        $res_or_exists = $this->get_image_upload_url($image_id);
        if ($res_or_exists->exists) {
            return true;
        } else {
            $this->put_data($res_or_exists->imgUploadRes->url, $buf);
            return false;
        }

    }//end upload_img()


    /**
     * @throws InternalServerException
     */
    public function upload_img_file(string $image_id, string $path): bool
    {
        $res_or_exists = $this->get_image_upload_url($image_id);
        if ($res_or_exists->exists) {
            return true;
        } else {
            $data = file_get_contents($path);
            $this->put_data($res_or_exists->imgUploadRes->url, $data);
            return false;
        }

    }//end upload_img_file()


    /**
     * @throws InternalServerException
     */
    public function upload_input(string $buf): string
    {
        $upload_data = $this->get_upload_url('inputs');
        $this->put_data($upload_data->url, $buf);
        return $upload_data->uuid;

    }//end upload_input()


    /**
     * @throws InternalServerException
     */
    public function upload_input_file(string $path): string
    {
        $upload_data = $this->get_upload_url('inputs');
        $data        = file_get_contents($path);
        $this->put_data($upload_data->url, $data);
        return $upload_data->uuid;

    }//end upload_input_file()


    /**
     * @throws InternalServerException
     */
    public function upload_receipt(string $buf): string
    {
        $upload_data = $this->get_upload_url('receipts');
        $this->put_data($upload_data->url, $buf);
        return $upload_data->uuid;

    }//end upload_receipt()


    /**
     * @throws InternalServerException
     */
    public function upload_receipt_file(string $path): string
    {
        $upload_data = $this->get_upload_url('receipts');
        $data        = file_get_contents($path);
        $this->put_data($upload_data->url, $data);
        return $upload_data->uuid;

    }//end upload_receipt_file()


    /**
     * @throws InternalServerException
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
                throw new InternalServerException($res->getBody());
            }

            $res = Responses\CreateSessRes::from_json($res->getBody());
            return $res->uuid;
        } catch (GuzzleException $e) {
            throw new InternalServerException($e);
        }//end try

    }//end create_session()


    /**
     * @throws InternalServerException
     */
    public function download(string $url): string
    {
        try {
            $res = $this->client->get($url);

            $status_code = $res->getStatusCode();
            if ($status_code < 200 || $status_code > 300) {
                throw new InternalServerException(
                    sprintf('cannot download the file: http status code %d', $status_code)
                );
            }

            return $res->getBody();
        } catch (GuzzleException $e) {
            throw new InternalServerException($e);
        }

    }//end download()


    /**
     * @throws InternalServerException
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
                throw new InternalServerException($res->getBody());
            }

            $res = Responses\CreateSessRes::from_json($res->getBody());
            return new SnarkId($res->uuid);
        } catch (GuzzleException $e) {
            throw new InternalServerException($e);
        }//end try

    }//end create_snark()


    /**
     * @throws InternalServerException
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
                throw new InternalServerException($res->getBody());
            }

            return Responses\VersionInfo::from_json($res->getBody());
        } catch (GuzzleException $e) {
            throw new InternalServerException($e);
        }

    }//end version()


    /**
     * @throws InternalServerException
     */
    public function quotas(): Responses\Quotas
    {
        try {
            $res = $this->client->get(
                sprintf('%s/user/quotas', $this->url)
            );

            $status_code = $res->getStatusCode();
            if ($status_code < 200 || $status_code > 300) {
                throw new InternalServerException($res->getBody());
            }

            return Responses\Quotas::from_json($res->getBody());
        } catch (GuzzleException $e) {
            throw new InternalServerException($e);
        }

    }//end quotas()


}//end class
