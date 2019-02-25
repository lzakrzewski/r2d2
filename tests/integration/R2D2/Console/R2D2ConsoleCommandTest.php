<?php

declare(strict_types=1);

namespace tests\integration\R2D2\Console;

use PHPUnit\Framework\TestCase;
use R2D2\Application;
use R2D2\Console\R2D2ConsoleCommand;
use Symfony\Component\Console\Tester\CommandTester;

class R2D2ConsoleCommandTest extends TestCase
{
    /** @var CommandTester */
    private $tester;

    /** @test */
    public function it_can_display_questions(): void
    {
        $this->executeConsoleCommand(['4']);

        $display = $this->tester->getDisplay();

        $this->assertEquals(0, $this->tester->getStatusCode());
        $this->assertContains('Create token', $display);
        $this->assertContains('Destroy reactor', $display);
        $this->assertContains('Release prisoner', $display);
    }

    /** @test */
    public function it_can_display_sub_questions(): void
    {
        $this->executeConsoleCommand(['1', 'R2D2', 'Alderan', '4']);

        $display = $this->tester->getDisplay();

        $this->assertEquals(0, $this->tester->getStatusCode());
        $this->assertContains('client-id', $display);
        $this->assertContains('client-secret', $display);
    }

    private function executeConsoleCommand(array $inputs = []): void
    {
        $this->tester->setInputs($inputs);
        $this->tester->execute([]);
    }

    protected function setUp(): void
    {
        $cli = (new Application('test'))->getContainer()->get(R2D2ConsoleCommand::class);

        $this->tester = new CommandTester($cli);
    }
}
