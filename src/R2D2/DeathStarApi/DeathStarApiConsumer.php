<?php

declare(strict_types=1);

namespace R2D2\DeathStarApi;

class DeathStarApiConsumer
{
    /** @var DeathStarApiAdapter */
    private $apiAdapter;

    /** @var TokenStorage */
    private $tokenStorage;

    public function __construct(DeathStarApiAdapter $apiAdapter, TokenStorage $tokenStorage)
    {
        $this->apiAdapter   = $apiAdapter;
        $this->tokenStorage = $tokenStorage;
    }

    public function createToken(string $clientId, string $clientSecret): void
    {
        $token = $this->apiAdapter->request(
            'POST',
            '/Token',
            [
                'headers' => [
                    'content-type' => 'application/x-www-form-urlencoded',
                ],
                'form_params' => [
                    'grant_type'    => 'client_credentials',
                    'client_id'     => $clientId,
                    'client_secret' => $clientSecret,
                ],
            ]
        );

        $this->tokenStorage->store($token['access_token']);
    }

    public function destroyReactor(int $exhaustId, int $numberOfTorpedoes): void
    {
        $this->apiAdapter->request(
            'DELETE',
            sprintf('/reactor/exhaust/%d', $exhaustId),
            [
                'headers' => [
                    'authorization' => sprintf('Bearer %s', $this->tokenStorage->get()),
                    'content-type'  => 'application/json',
                    'x-torpedoes'   => (string) $numberOfTorpedoes,
                ],
            ]
        );
    }

    public function releasePrisoner(string $prisonerId): array
    {
        return $this->apiAdapter->request(
            'GET',
            sprintf('/prisoner/%s', $prisonerId),
            [
                'headers' => [
                    'authorization' => sprintf('Bearer %s', $this->tokenStorage->get()),
                    'content-type'  => 'application/json',
                ],
            ]
        );
    }
}
