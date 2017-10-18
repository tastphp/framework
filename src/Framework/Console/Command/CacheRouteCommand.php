<?php

namespace TastPHP\Framework\Console\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use TastPHP\Framework\Kernel;
use TastPHP\Framework\Router\RouterServiceProvider;

class CacheRouteCommand extends BaseCommand
{
    protected function configure()
    {
        $this
            ->setName('cache:route')
            ->setDescription('Cache route');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $routerService = new RouterServiceProvider(new Kernel());
        $routerService->register();
        $output->writeln("<fg=black;bg=green>You have success cached route!</>");
    }
}