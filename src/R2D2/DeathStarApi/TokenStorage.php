<?php

declare(strict_types=1);

namespace R2D2\DeathStarApi;

use R2D2\DeathStarApi\Exception\DeathStarApiException;

interface TokenStorage
{
    /**
     * @param string $accessToken
     */
    public function store(string $accessToken): void;

    /**
     * @return string
     *
     * @throws DeathStarApiException
     */
    public function get(): string;
}
