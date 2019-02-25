<?php

declare(strict_types=1);

namespace tests\unit\R2D2\DeathStarApi\TokenStorage;

use PHPStan\Testing\TestCase;
use R2D2\DeathStarApi\Exception\DeathStarApiException;
use R2D2\DeathStarApi\TokenStorage\InMemoryTokenStorage;

class InMemoryTokenStorageTest extends TestCase
{
    /** @var InMemoryTokenStorage */
    private $tokenStorage;

    /** @test */
    public function it_can_get_token(): void
    {
        $this->tokenStorage->store('access-token');

        $this->assertEquals('access-token', $this->tokenStorage->get());
    }

    /** @test */
    public function it_fails_when_token_does_not_exists(): void
    {
        $this->expectException(DeathStarApiException::class);

        $this->tokenStorage->get();
    }

    protected function setUp(): void
    {
        $this->tokenStorage = new InMemoryTokenStorage();
    }
}
