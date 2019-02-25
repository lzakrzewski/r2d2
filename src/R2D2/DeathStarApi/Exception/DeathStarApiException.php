<?php

declare(strict_types=1);

namespace R2D2\DeathStarApi\Exception;

class DeathStarApiException extends \Exception
{
    public static function apiError(string $message, \Throwable $previous): self
    {
        return new self($message, 0, $previous);
    }
}
