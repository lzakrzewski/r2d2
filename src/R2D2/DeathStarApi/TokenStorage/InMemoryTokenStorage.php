<?php

declare(strict_types=1);

namespace R2D2\DeathStarApi\TokenStorage;

use R2D2\DeathStarApi\Exception\NotFoundAccessTokenException;
use R2D2\DeathStarApi\TokenStorage;

class InMemoryTokenStorage implements TokenStorage
{
    /** @var string */
    private $accessToken;

    public function store(string $accessToken): void
    {
        $this->accessToken = $accessToken;
    }

    public function get(): string
    {
        if (null === $this->accessToken) {
            throw NotFoundAccessTokenException::create();
        }

        return $this->accessToken;
    }
}
