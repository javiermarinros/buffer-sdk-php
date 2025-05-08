<?php

namespace BufferSDK\Http;

use BufferSDK\Auth\AuthorizationTokenInterface;

class Client implements ClientInterface
{
    /** @var string */
    protected $baseURL = 'https://api.bufferapp.com/1/';

    /** @var \GuzzleHttp\Client */
    private $httpClient;

    /**
     * Client constructor.
     */
    public function __construct(AuthorizationTokenInterface $auth)
    {
        $this->httpClient = new \GuzzleHttp\Client([
            'base_uri' => $this->baseURL,
            \GuzzleHttp\RequestOptions::HEADERS => [
                'Authorization' => 'Bearer ' . $auth->getAccessToken(),
            ],
        ]);
    }

    /**
     * Create Http Request and send the request.
     *
     * @param string $method
     * @param string $endpoint
     * @param array $options
     *
     * @return array
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function createHttpRequest(string $method, string $endpoint, array $options = []): array
    {
        $response = $this->httpClient->request($method, $endpoint, $options);
        $responseBody = json_decode($response->getBody()->getContents(), true, 512, JSON_BIGINT_AS_STRING);

        if (\JSON_ERROR_NONE !== json_last_error()) {
            throw new \Exception(json_last_error_msg() . sprintf(' for "%s".', $method), json_last_error());
        }

        return $responseBody;
    }
}
