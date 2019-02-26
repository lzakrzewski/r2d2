<?php

declare(strict_types=1);

namespace tests\contract\R2D2\DeathStarApi;

use PHPStan\Testing\TestCase;
use R2D2\Application;
use R2D2\DeathStarApi\DeathStarApiConsumer;
use R2D2\DeathStarApi\Exception\DeathStarApiException;
use R2D2\DeathStarApi\TokenStorage;

class DeathStarApiConsumerTest extends TestCase
{
    /** @var TokenStorage */
    private $tokenStorage;

    /** @var DeathStarApiConsumer */
    private $apiConsumer;

    /** @test */
    public function it_can_create_token(): void
    {
        $this->apiConsumer->createToken('client-id', 'client-secret');

        $this->assertNotEmpty($this->tokenStorage->get());
    }

    /** @test */
    public function it_can_destroy_reactor(): void
    {
        $this->authenticate();

        $this->apiConsumer->destroyReactor(1, 1);

        $this->assertTrue(true);
    }

    /** @test */
    public function it_can_not_destroy_reactor_without_authentication(): void
    {
        $this->expectException(DeathStarApiException::class);

        $this->apiConsumer->destroyReactor(1, 1);
    }

    /** @test */
    public function it_can_release_prisoner(): void
    {
        $this->authenticate();

        $response = $this->apiConsumer->releasePrisoner('leila');

        $this->assertArrayHasKey('block', $response);
        $this->assertArrayHasKey('cell', $response);
    }

    /** @test */
    public function it_can_not_release_prisoner_without_authentication(): void
    {
        $this->expectException(DeathStarApiException::class);

        $this->apiConsumer->releasePrisoner('leila');
    }

    protected function setUp(): void
    {
        $container = (new Application('test'))->getContainer();

        $this->tokenStorage = $container->get(TokenStorage::class);
        $this->apiConsumer  = $container->get(DeathStarApiConsumer::class);
    }

    private function authenticate(): string
    {
        $this->apiConsumer->createToken('client-id', 'client-secret');

        return $this->tokenStorage->get();
    }
}
