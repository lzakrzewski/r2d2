<?php

declare(strict_types=1);

namespace R2D2\DeathStarApi\Adapter;

use DeathStar\Contracts\Exception\ContractViolationException;
use DeathStar\Contracts\RandomizedResponse;
use DeathStar\Contracts\RequestValidator;
use R2D2\DeathStarApi\DeathStarApiAdapter;
use R2D2\DeathStarApi\Exception\DeathStarApiException;

class UsesContractsDeathStarApiAdapterStub implements DeathStarApiAdapter
{
    /** @var RequestValidator */
    private $requestValidator;

    /** @var RandomizedResponse */
    private $randomizedResponse;

    public function __construct(RequestValidator $requestValidator, RandomizedResponse $randomizedResponse)
    {
        $this->requestValidator   = $requestValidator;
        $this->randomizedResponse = $randomizedResponse;
    }

    public function request(string $method, string $uri, array $options): array
    {
        try {
            $this->requestValidator->validateRequest($method, $uri, $options);

            return $this->randomizedResponse->getResponseFor($method, $uri);
        } catch (ContractViolationException $error) {
            throw DeathStarApiException::apiError($error->getMessage(), $error);
        }
    }
}
