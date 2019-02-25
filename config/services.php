<?php

declare(strict_types=1);

use R2D2\Console\R2D2ConsoleCommand;
use R2D2\DeathStarApi\DeathStarApiConsumer;
use Symfony\Component\DependencyInjection\Reference;

$container
    ->register(DeathStarApiConsumer::class, DeathStarApiConsumer::class);

$container
    ->register(R2D2ConsoleCommand::class, R2D2ConsoleCommand::class)
    ->setPublic(true)
    ->addTag('console')
    ->addArgument(new Reference(DeathStarApiConsumer::class));
