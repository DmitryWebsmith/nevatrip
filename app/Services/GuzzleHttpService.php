<?php
namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Symfony\Component\HttpKernel\Exception\HttpException;

class GuzzleHttpService
{
    public function sendRequest(
        string $endPoint,
        array $body = [],
        array $headers = [],
        array $options = [],
        string $method = 'POST'
    ): mixed {
        $sslVerify = false;

        if (config('app.env') === 'production') {
            $sslVerify = true;
        }

        if (count($body) == 0) {
            $request = new Request($method, $endPoint, $headers);
        } else {
            $request = new Request($method, $endPoint, $headers, json_encode($body));
        }

        $client = new Client(
            [
                'headers' => $headers,
                'http_errors' => false,
                'verify' => $sslVerify,
            ]
        );

        try {
            if (count($options) == 0) {
                $response = $client->sendAsync($request)->wait();
            } else {
                $response = $client->sendAsync($request, $options)->wait();
            }
        } catch (\Exception $exception) {
            logger()->error(
                "GuzzleHttpService | sendRequest | Exception",
                [
                    "exception" => $exception->getMessage(),
                    "endPoint" => $endPoint,
                    "method" => $method,
                ]
            );
            throw new HttpException(
                ResponseAlias::HTTP_UNAUTHORIZED,
                $exception->getMessage()
            );
        }

        return $response;
    }
}
