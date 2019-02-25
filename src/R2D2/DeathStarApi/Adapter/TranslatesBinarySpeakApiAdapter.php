<?php

declare(strict_types=1);

namespace R2D2\DeathStarApi\Adapter;

use DroidSpeak\DroidSpeakTranslator;
use DroidSpeak\Exception\CanNotTranslateInputException;
use Psr\Log\LoggerInterface;
use R2D2\DeathStarApi\DeathStarApiAdapter;
use R2D2\DeathStarApi\Exception\DeathStarApiException;

class TranslatesBinarySpeakApiAdapter implements DeathStarApiAdapter
{
    /** @var DeathStarApiAdapter */
    private $apiAdapter;

    /** @var DroidSpeakTranslator */
    private $translator;

    /** @var LoggerInterface */
    private $logger;

    public function __construct(
        DeathStarApiAdapter $apiAdapter,
        DroidSpeakTranslator $translator,
        LoggerInterface $logger
    ) {
        $this->apiAdapter     = $apiAdapter;
        $this->translator     = $translator;
        $this->logger         = $logger;
    }

    public function request(string $method, string $uri, array $options): array
    {
        $response = $this->apiAdapter->request($method, $uri, $options);

        if ('get' !== strtolower($method)) {
            return $response;
        }

        try {
            $response = $this->translator->translate($response);

            $this->logger->info(sprintf('Successfully decoded response: "%s"', json_encode($response)));

            return $response;
        } catch (CanNotTranslateInputException $error) {
            throw DeathStarApiException::apiError($error->getMessage(), $error);
        }
    }
}
