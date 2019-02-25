<?php

declare(strict_types=1);

namespace tests\unit\R2D2\DeathStarApi\Adapter;

use PHPStan\Testing\TestCase;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\Log\LoggerInterface;
use R2D2\DeathStarApi\Adapter\LoggableDeathStarApiAdapter;
use R2D2\DeathStarApi\DeathStarApiAdapter;
use R2D2\DeathStarApi\Exception\DeathStarApiException;

class LoggableDeathStarApiAdapterTest extends TestCase
{
    /** @var LoggerInterface|ObjectProphecy */
    private $logger;

    /** @var DeathStarApiAdapter|ObjectProphecy */
    private $apiAdapter;

    /** @var LoggableDeathStarApiAdapter */
    private $loggableAdapter;

    /** @test */
    public function it_can_log_request(): void
    {
        $this->apiAdapter
            ->request('GET', '/prisoner/lea', [])
            ->willReturn([
                'block' => 'a',
                'cell'  => 'b',
            ]);

        $this->loggableAdapter->request('GET', '/prisoner/lea', []);

        $this->logger
            ->info('Sending [GET] request to "/prisoner/lea"')
            ->shouldBeCalled();
    }

    /** @test */
    public function it_can_log_response(): void
    {
        $this->apiAdapter
            ->request('GET', '/prisoner/lea', [])
            ->willReturn([
                'block' => 'a',
                'cell'  => 'b',
            ]);

        $this->loggableAdapter->request('GET', '/prisoner/lea', []);

        $this->logger
            ->info('Successfully received response: "{"block":"a","cell":"b"}"')
            ->shouldBeCalled();
    }

    /** @test */
    public function it_can_log_errors(): void
    {
        $this->apiAdapter
            ->request('GET', '/prisoner/lea', [])
            ->willThrow(new DeathStarApiException('some-error'));

        try {
            $this->loggableAdapter->request('GET', '/prisoner/lea', []);

            $this->fail('Exception is expected');
        } catch (\Throwable $exception) {
        }

        $this->logger
            ->error('Error for request [GET] request to "/prisoner/lea": "some-error"')
            ->shouldBeCalled();
    }

    protected function setUp(): void
    {
        $this->logger     = $this->prophesize(LoggerInterface::class);
        $this->apiAdapter = $this->prophesize(DeathStarApiAdapter::class);

        $this->loggableAdapter = new LoggableDeathStarApiAdapter(
            $this->apiAdapter->reveal(),
            $this->logger->reveal()
        );
    }
}
