<?php

declare(strict_types=1);

namespace tests\unit\R2D2\DeathStarApi\Adapter;

use DroidSpeak\DroidSpeakTranslator;
use DroidSpeak\Exception\CanNotTranslateInputException;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Psr\Log\LoggerInterface;
use R2D2\DeathStarApi\Adapter\TranslatesBinarySpeakApiAdapter;
use R2D2\DeathStarApi\DeathStarApiAdapter;
use R2D2\DeathStarApi\Exception\DeathStarApiException;

class TranslatesBinarySpeakApiAdapterTest extends TestCase
{
    /** @var DeathStarApiAdapter */
    private $apiAdapter;

    /** @var DroidSpeakTranslator */
    private $translator;

    /** @var TranslatesBinarySpeakApiAdapter */
    private $translatorAdapter;

    /** @var LoggerInterface */
    private $logger;

    /** @test */
    public function it_can_translate_get_requests(): void
    {
        $this->apiAdapter->request('GET', '/prisoner/lea', [])
            ->willReturn($before = ['before-translation']);

        $this->translator->translate($before)
            ->willReturn($after = ['after-translation']);

        $this->logger->info(Argument::any())->shouldBeCalled();

        $this->assertEquals($after, $this->translatorAdapter->request('GET', '/prisoner/lea', []));
    }

    /** @test */
    public function it_does_not_translate_other_requests(): void
    {
        $this->apiAdapter->request('POST', '/prisoner/lea', [])
            ->willReturn($before = ['before-translation']);

        $this->translator->translate($before)
            ->shouldNotBeCalled();

        $this->assertEquals($before, $this->translatorAdapter->request('POST', '/prisoner/lea', []));
    }

    /** @test */
    public function it_fails_when_translation_fails(): void
    {
        $this->expectException(DeathStarApiException::class);

        $this->apiAdapter->request('GET', '/prisoner/lea', [])
            ->willReturn($before = ['before-translation']);

        $this->translator->translate($before)
            ->willThrow(CanNotTranslateInputException::class);

        $this->translatorAdapter->request('GET', '/prisoner/lea', []);
    }

    protected function setUp(): void
    {
        $this->apiAdapter   = $this->prophesize(DeathStarApiAdapter::class);
        $this->translator   = $this->prophesize(DroidSpeakTranslator::class);
        $this->logger       = $this->prophesize(LoggerInterface::class);

        $this->translatorAdapter = new TranslatesBinarySpeakApiAdapter(
            $this->apiAdapter->reveal(),
            $this->translator->reveal(),
            $this->logger->reveal()
        );
    }
}
