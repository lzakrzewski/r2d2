<?php

declare(strict_types=1);

use DeathStar\Contracts\ContractsReader;
use DeathStar\Contracts\RequestValidator;
use R2D2\DeathStarApi\Adapter\UsesContractsDeathStarApiAdapterStub;
use R2D2\DeathStarApi\DeathStarApiAdapter;
use Symfony\Component\DependencyInjection\Reference;
use DeathStar\Contracts\RandomizedResponse;

//Parameters:
$container
    ->setParameter('death_star.contracts.contract_file_path', __DIR__ . '/../vendor/lzakrzewski/death-star-contracts/src/DeathStar/Contracts/contract.json');

$container
    ->setParameter('death_star.base_url', 'localhost');

$container
    ->register(ContractsReader::class, ContractsReader::class)
    ->addArgument('%death_star.contracts.contract_file_path%');

$container
    ->register(RequestValidator::class, RequestValidator::class)
    ->addArgument(new Reference(ContractsReader::class));

$container
    ->register(RandomizedResponse::class, RandomizedResponse::class)
    ->addArgument(new Reference(ContractsReader::class));

$container
    ->register(DeathStarApiAdapter::class, UsesContractsDeathStarApiAdapterStub::class)
    ->addArgument(new Reference(RequestValidator::class))
    ->addArgument(new Reference(RandomizedResponse::class));
