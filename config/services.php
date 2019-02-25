<?php

declare(strict_types=1);

use R2D2\Console\R2D2ConsoleCommand;
use R2D2\DeathStarApi\DeathStarApiConsumer;
use R2D2\DeathStarApi\DeathStarApiAdapter;
use Symfony\Component\DependencyInjection\Reference;
use R2D2\DeathStarApi\Adapter\HttpDeathStarApiAdapter;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Client;
use R2D2\DeathStarApi\TokenStorage;
use R2D2\DeathStarApi\TokenStorage\InMemoryTokenStorage;
use R2D2\DeathStarApi\Adapter\LoggableDeathStarApiAdapter;
use Symfony\Component\Console\Logger\ConsoleLogger;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\OutputInterface;
use R2D2\DeathStarApi\Adapter\TranslatesBinarySpeakApiAdapter;
use DroidSpeak\DroidSpeakTranslator;

$container
    ->register(ClientInterface::class, Client::class)
    ->addArgument(['base_uri' => 'https://death-star-api.herokuapp.com']);

$container
    ->register(HttpDeathStarApiAdapter::class, HttpDeathStarApiAdapter::class)
    ->addArgument(new Reference(ClientInterface::class));

$container
    ->register(OutputInterface::class, ConsoleOutput::class)
    ->addArgument(OutputInterface::VERBOSITY_VERY_VERBOSE);

$container
    ->register(LoggerInterface::class, ConsoleLogger::class)
    ->addArgument(new Reference(OutputInterface::class));

$container
    ->register(DroidSpeakTranslator::class, DroidSpeakTranslator::class);

$container
    ->register(LoggableDeathStarApiAdapter::class, LoggableDeathStarApiAdapter::class)
    ->addArgument(new Reference(HttpDeathStarApiAdapter::class))
    ->addArgument(new Reference(LoggerInterface::class));

$container
    ->register(DeathStarApiAdapter::class, TranslatesBinarySpeakApiAdapter::class)
    ->addArgument(new Reference(LoggableDeathStarApiAdapter::class))
    ->addArgument(new Reference(DroidSpeakTranslator::class))
    ->addArgument(new Reference(LoggerInterface::class));

$container
    ->register(TokenStorage::class, InMemoryTokenStorage::class);

$container
    ->register(DeathStarApiConsumer::class, DeathStarApiConsumer::class)
    ->addArgument(new Reference(DeathStarApiAdapter::class))
    ->addArgument(new Reference(TokenStorage::class));

$container
    ->register(R2D2ConsoleCommand::class, R2D2ConsoleCommand::class)
    ->setPublic(true)
    ->addTag('console')
    ->addArgument(new Reference(DeathStarApiConsumer::class));
