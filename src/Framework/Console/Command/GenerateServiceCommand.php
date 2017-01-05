<?php
namespace TastPHP\Framework\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class GenerateServiceCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('generate:service')
            ->setDescription('Generate a service (register into app)')
            ->addArgument(
                'name',
                InputArgument::OPTIONAL,
                'Service name'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        chdir(__DIR__ . '/../Core');
        $helper = $this->getHelper('question');
        $question = new Question('Please enter the name of service:', 'demoservice');
        $name = $helper->ask($input, $output, $question);
        $fs = new Filesystem();
        $ucName = ucfirst($name);
        $lcName = lcfirst($name);
        $fs->mkdir("$ucName");

        $serviceContent = file_get_contents(__DIR__ . "/Template/demoservice.txt");
        $serviceContent = str_replace('{{demoservice}}', $lcName, $serviceContent);
        $serviceContent = str_replace('{{Demoservice}}', $ucName, $serviceContent);
        $fs->dumpFile("$ucName/$ucName.php", $serviceContent);


        $serviceContent = file_get_contents(__DIR__ . "/Template/demoserviceService.txt");
        $serviceContent = str_replace('{{demoservice}}', $lcName, $serviceContent);
        $serviceContent = str_replace('{{Demoservice}}', $ucName, $serviceContent);
        $fs->dumpFile("$ucName/{$ucName}Service.php", $serviceContent);

        $serviceContent = file_get_contents(__DIR__ . "/Template/demoserviceServiceProvider.txt");
        $serviceContent = str_replace('{{demoservice}}', $lcName, $serviceContent);
        $serviceContent = str_replace('{{Demoservice}}', $ucName, $serviceContent);
        $fs->dumpFile("$ucName/{$ucName}ServiceProvider.php", $serviceContent);

        $output->writeln("<fg=black;bg=green>You have success generate {$ucName}Service</>");
    }


}