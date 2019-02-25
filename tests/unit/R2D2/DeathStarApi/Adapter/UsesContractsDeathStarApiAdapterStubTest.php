<?php

declare(strict_types=1);

namespace tests\unit\R2D2\DeathStarApi\Adapter;

use DeathStar\Contracts\Exception\InvalidRequestException;
use DeathStar\Contracts\Exception\NotFoundContractException;
use DeathStar\Contracts\RandomizedResponse;
use DeathStar\Contracts\RequestValidator;
use PHPUnit\Framework\TestCase;
use R2D2\DeathStarApi\Adapter\UsesContractsDeathStarApiAdapterStub;
use R2D2\DeathStarApi\Exception\DeathStarApiException;

class UsesContractsDeathStarApiAdapterStubTest extends TestCase
{
    /** @var RequestValidator */
    private $requestValidator;

    /** @var RandomizedResponse */
    private $randomizedResponse;

    /** @var UsesContractsDeathStarApiAdapterStub */
    private $adapter;

    /** @test */
    public function it_can_validate_request_and_return_response_from_contract(): void
    {
        $this->requestValidator
            ->validateRequest('GET', '/prisoner/leila', [])
            ->shouldBeCalled();

        $this->randomizedResponse
            ->getResponseFor('GET', '/prisoner/leila')
            ->willReturn(['some-response']);

        $response = $this->adapter->request('GET', '/prisoner/leila', []);

        $this->assertEquals(['some-response'], $response);
    }

    /** @test */
    public function it_fails_when_request_does_not_match_contract(): void
    {
        $this->expectException(DeathStarApiException::class);

        $this->requestValidator
            ->validateRequest('GET', '/prisoner/leila', [])
            ->willThrow(InvalidRequestException::class);

        $this->randomizedResponse
            ->getResponseFor('GET', '/prisoner/leila')
            ->willReturn(['some-response']);

        $this->adapter->request('GET', '/prisoner/leila', []);
    }

    /** @test */
    public function it_fails_when_contract_does_not_exist(): void
    {
        $this->expectException(DeathStarApiException::class);

        $this->requestValidator
            ->validateRequest('GET', '/prisoner/leila', [])
            ->shouldBeCalled();

        $this->randomizedResponse
            ->getResponseFor('GET', '/prisoner/leila')
            ->willThrow(NotFoundContractException::class);

        $this->adapter->request('GET', '/prisoner/leila', []);
    }

    protected function setUp(): void
    {
        $this->requestValidator   = $this->prophesize(RequestValidator::class);
        $this->randomizedResponse = $this->prophesize(RandomizedResponse::class);

        $this->adapter = new UsesContractsDeathStarApiAdapterStub(
            $this->requestValidator->reveal(),
            $this->randomizedResponse->reveal()
        );
    }
}
