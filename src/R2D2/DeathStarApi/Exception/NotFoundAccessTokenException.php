<?php

declare(strict_types=1);

namespace R2D2\DeathStarApi\Exception;

class NotFoundAccessTokenException extends DeathStarApiException
{
    public static function create(): self
    {
        return new self('The access token not found.');
    }
}
