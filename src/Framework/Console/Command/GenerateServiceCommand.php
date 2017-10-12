<?php

namespace TastPHP\Framework\Console\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class GenerateServiceCommand extends BaseCommand
{
    protected function configure()
    {
        $this
            ->setName('generate:service')
            ->setDescription('Generates a service (register into app)')
            ->addArgument(
                'name',
                InputArgument::OPTIONAL,
                'Service name'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $helper = $this->getHelper('question');
        $question = new Question('Please enter the name of service (default:demo):', 'demo');
        $name = $helper->ask($input, $output, $question);
        $filesystem = new Filesystem();
        $ucName = ucfirst($name);
        $lcName = lcfirst($name);
        $filesystem->mkdir("$ucName");

        $appDir = __EXPORT_DIR__ . "/src/App";
        $providerDir = $appDir . "/" . $ucName;
        $templateDir = $this->getTemplateDir();
        $serviceContent = file_get_contents($templateDir . "/demoservice.txt");
        $serviceContent = str_replace('{{demoservice}}', $lcName, $serviceContent);
        $serviceContent = str_replace('{{Demoservice}}', $ucName, $serviceContent);
        $filesystem->dumpFile($providerDir . "/$ucName.php", $serviceContent);

        $serviceContent = file_get_contents($templateDir . "/demoserviceService.txt");
        $serviceContent = str_replace('{{demoservice}}', $lcName, $serviceContent);
        $serviceContent = str_replace('{{Demoservice}}', $ucName, $serviceContent);
        $filesystem->dumpFile($providerDir . "/{$ucName}Service.php", $serviceContent);

        $serviceContent = file_get_contents($templateDir . "/demoserviceServiceProvider.txt");
        $serviceContent = str_replace('{{demoservice}}', $lcName, $serviceContent);
        $serviceContent = str_replace('{{Demoservice}}', $ucName, $serviceContent);
        $filesystem->dumpFile($providerDir . "/{$ucName}ServiceProvider.php", $serviceContent);

        $output->writeln("<fg=black;bg=green>You have success generate {$ucName}Service</>");
    }
}