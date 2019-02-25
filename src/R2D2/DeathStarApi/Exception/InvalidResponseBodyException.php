<?php

declare(strict_types=1);

namespace R2D2\DeathStarApi\Exception;

class InvalidResponseBodyException extends DeathStarApiException
{
    public static function create(string $body): self
    {
        return new self(sprintf('Can not decode given "%s" response body. The json is invalid.', $body));
    }
}
