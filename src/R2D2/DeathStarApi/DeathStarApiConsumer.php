<?php

declare(strict_types=1);

namespace R2D2\DeathStarApi;

class DeathStarApiConsumer
{
    /** @var DeathStarApiAdapter */
    private $apiAdapter;

    /** @var TokenStorage */
    private $authorizationToken;

    /** @var array */
    private $certificates;

    public function __construct(
        DeathStarApiAdapter $apiAdapter,
        TokenStorage $authorizationToken,
        array $certificates
    ) {
        $this->apiAdapter         = $apiAdapter;
        $this->authorizationToken = $authorizationToken;
        $this->certificates       = $certificates;
    }

    public function createToken(string $clientId, string $clientSecret): void
    {
        $token = $this->apiAdapter->request(
            'POST',
            '/Token',
            [
                'cert'    => $this->certificates['cert'],
                'ssl_key' => $this->certificates['ssl_key'],
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

        $this->authorizationToken->store($token['access_token']);
    }

    public function destroyReactor(int $exhaustId, int $numberOfTorpedoes): void
    {
        $this->apiAdapter->request(
            'DELETE',
            sprintf('/reactor/exhaust/%d', $exhaustId),
            [
                'cert'    => $this->certificates['cert'],
                'ssl_key' => $this->certificates['ssl_key'],
                'headers' => [
                    'authorization' => sprintf('Bearer %s', $this->authorizationToken->get()),
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
                'cert'    => $this->certificates['cert'],
                'ssl_key' => $this->certificates['ssl_key'],
                'headers' => [
                    'authorization' => sprintf('Bearer %s', $this->authorizationToken->get()),
                    'content-type'  => 'application/json',
                ],
            ]
        );
    }
}
