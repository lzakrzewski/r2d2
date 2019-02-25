<?php

declare(strict_types=1);

namespace tests\unit\R2D2\DeathStarApi\Adapter;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use R2D2\DeathStarApi\Adapter\HttpDeathStarApiAdapter;
use R2D2\DeathStarApi\Exception\DeathStarApiException;

class HttpDeathStarApiAdapterTest extends TestCase
{
    /** @var ClientInterface */
    private $guzzleClient;

    /** @var HttpDeathStarApiAdapter */
    private $adapter;

    /** @test */
    public function it_can_send_request_and_return_decoded_response(): void
    {
        $this->guzzleClient->request('GET', '/prisoner/leila', [])
            ->willReturn(new Response(200, [], '{"some": "response"}'));

        $response = $this->adapter->request('GET', '/prisoner/leila', []);

        $this->assertEquals(['some' => 'response'], $response);
    }

    /** @test */
    public function it_fails_when_api_fails(): void
    {
        $this->expectException(DeathStarApiException::class);

        $this->guzzleClient->request('GET', '/prisoner/leila', [])
            ->willThrow(RequestException::class);

        $this->adapter->request('GET', '/prisoner/leila', []);
    }

    /** @test */
    public function it_fails_when_response_is_invalid(): void
    {
        $this->expectException(DeathStarApiException::class);

        $this->guzzleClient->request('GET', '/prisoner/leila', [])
            ->willReturn(new Response(200, [], '{invalidJson}'));

        $this->adapter->request('GET', '/prisoner/leila', []);
    }

    protected function setUp(): void
    {
        $this->guzzleClient = $this->prophesize(ClientInterface::class);
        $this->adapter      = new HttpDeathStarApiAdapter($this->guzzleClient->reveal());
    }
}
