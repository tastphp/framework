<?php

namespace TastPHP\Framework\Console\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use TastPHP\Framework\Config\ConfigService;
use TastPHP\Framework\Kernel;

class CacheConfigCommand extends BaseCommand
{
    protected function configure()
    {
        $this
            ->setName('cache:config')
            ->setDescription('Cache config');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $configService = new ConfigService(new Kernel());
        $configService->setEnabledCache();
        $configService->register();
        $output->writeln("<fg=black;bg=green>You have success cached config!</>");
    }
}