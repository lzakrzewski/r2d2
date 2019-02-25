<?php

declare(strict_types=1);

namespace R2D2;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\Console\Application as ConsoleApplication;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;
use Symfony\Component\DependencyInjection\TaggedContainerInterface;

final class Application extends ConsoleApplication
{
    /** @var TaggedContainerInterface */
    private $container;

    /** @var string */
    private $env;

    public function __construct(string $env)
    {
        parent::__construct('r2d2', '0');

        $this->env       = $env;
        $this->container = new ContainerBuilder();

        $this->setUpContainer();

        $inputDefinition = $this->getDefinition();
        $inputDefinition->addOption(new InputOption('--env', '-e', InputOption::VALUE_REQUIRED, 'The Environment name.', $env));
    }

    private function setUpContainer(): void
    {
        $loader = new PhpFileLoader($this->container, new FileLocator(__DIR__.'/../../config'));
        $loader->load('services.php');

        if ('test' === $this->env) {
            $loader->load('services.test.php');
        }

        foreach ($this->container->findTaggedServiceIds('console') as $commandId => $command) {
            $this->add($this->container->get($commandId));
        }
    }

    public function getContainer(): TaggedContainerInterface
    {
        return $this->container;
    }
}
