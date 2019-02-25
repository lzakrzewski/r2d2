<?php

declare(strict_types=1);

namespace R2D2\DeathStarApi;

class DeathStarApiConsumer
{
    public function createToken(string $clientId, string $clientSecret): void
    {
    }

    public function destroyReactor(int $exhaustId, int $numberOfTorpedoes): void
    {
    }

    public function releasePrisoner(string $prisonerId): array
    {
        return ['some-response'];
    }
}
