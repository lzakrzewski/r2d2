<?php

declare(strict_types=1);

namespace R2D2\Console;

use R2D2\DeathStarApi\DeathStarApiConsumer;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class R2D2ConsoleCommand extends Command
{
    const CHOICES = [
        1 => 'Create token',
        2 => 'Destroy reactor',
        3 => 'Release prisoner',
        4 => 'Exit',
    ];

    /** @var DeathStarApiConsumer */
    private $api;

    public function __construct(DeathStarApiConsumer $api)
    {
        parent::__construct('r2d2');

        $this->api = $api;
    }

    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $this->askQuestionsAndCallDeathStarApi($input, $output);
    }

    private function askQuestionsAndCallDeathStarApi(InputInterface $input, OutputInterface $output): void
    {
        $io     = new SymfonyStyle($input, $output);
        $answer = $io->choice('What you would like to do?', self::CHOICES);

        switch ($answer) {
            case self::CHOICES[1]:
                $this->api->createToken($io->ask('client-id', 'R2D2'), $io->ask('client-secret', 'Alderan'));

                break;
            case self::CHOICES[2]:
                $this->api->destroyReactor((int) $io->ask('reactor-id', '1'), (int) $io->ask('number-of-torpedoes', '2'));

                break;
            case self::CHOICES[3]:
                $this->api->releasePrisoner($io->ask('prisoner-id', 'leia'));

                break;
            default:
                return;
                break;
        }

        $this->askQuestionsAndCallDeathStarApi($input, $output);
    }
}
