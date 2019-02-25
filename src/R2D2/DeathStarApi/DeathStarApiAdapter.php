<?php

declare(strict_types=1);

namespace R2D2\DeathStarApi;

use R2D2\DeathStarApi\Exception\DeathStarApiException;

interface DeathStarApiAdapter
{
    /**
     * @param string $method
     * @param string $uri
     * @param array  $options
     *
     * @throws DeathStarApiException
     *
     * @return array
     */
    public function request(string $method, string $uri, array $options): array;
}
