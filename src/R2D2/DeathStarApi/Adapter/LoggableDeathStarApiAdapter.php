<?php

declare(strict_types=1);

namespace R2D2\DeathStarApi\Adapter;

use Psr\Log\LoggerInterface;
use R2D2\DeathStarApi\DeathStarApiAdapter;
use R2D2\DeathStarApi\Exception\DeathStarApiException;

class LoggableDeathStarApiAdapter implements DeathStarApiAdapter
{
    /** @var DeathStarApiAdapter */
    private $apiAdapter;

    /** @var LoggerInterface */
    private $logger;

    public function __construct(DeathStarApiAdapter $apiAdapter, LoggerInterface $logger)
    {
        $this->apiAdapter = $apiAdapter;
        $this->logger     = $logger;
    }

    public function request(string $method, string $uri, array $options): array
    {
        $this->logger->info(sprintf('Sending [%s] request to "%s"', $method, $uri));

        try {
            $response = $this->apiAdapter->request($method, $uri, $options);
        } catch (DeathStarApiException $apiException) {
            $this->logger->error(sprintf('Error for request [%s] request to "%s": "%s"', $method, $uri, $apiException->getMessage()));

            throw $apiException;
        }

        $this->logger->info(sprintf('Successfully received response: "%s"', json_encode($response)));

        return $response;
    }
}
