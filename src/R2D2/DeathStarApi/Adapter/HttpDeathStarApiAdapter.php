<?php

declare(strict_types=1);

namespace R2D2\DeathStarApi\Adapter;

use GuzzleHttp\ClientInterface;
use Psr\Http\Message\ResponseInterface;
use R2D2\DeathStarApi\DeathStarApiAdapter;
use R2D2\DeathStarApi\Exception\DeathStarApiException;
use R2D2\DeathStarApi\Exception\InvalidResponseBodyException;

class HttpDeathStarApiAdapter implements DeathStarApiAdapter
{
    /** @var ClientInterface */
    private $client;

    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    public function request(string $method, string $uri, array $options): array
    {
        try {
            $response = $this->client->request($method, $uri, $options);
        } catch (\Throwable $error) {
            throw DeathStarApiException::apiError($error->getMessage(), $error);
        }

        return $this->decodeResponse($response);
    }

    private function decodeResponse(ResponseInterface $response): array
    {
        $body = $response->getBody()->getContents();

        if (empty($body)) {
            return [];
        }

        $decodedResponse = json_decode($body, true);

        if (null === $decodedResponse) {
            throw InvalidResponseBodyException::create($body);
        }

        return (array) $decodedResponse;
    }
}
