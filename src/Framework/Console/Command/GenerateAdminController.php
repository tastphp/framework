<?php

namespace TastPHP\Framework\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;

class GenerateAdminController extends Command
{
    protected function configure()
    {
        $this
            ->setName('generate:adminController')
            ->setDescription('Generates a admin controller');

    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        chdir(__BASEDIR__ . '/src/');

        $helper = $this->getHelper('question');

        $question = new Question('Please enter the name of entity(default:demo_test)', 'demo_test');
        $entityName = $helper->ask($input, $output, $question);
        $controllerName = $this->getControllerNameByEntityName($entityName);
        $filesystem = new Filesystem();
        $controllerContent = file_get_contents("App/Console/Command/Template/adminController.txt");
        $controllerContent = str_replace("Entity",$controllerName,$controllerContent);
        $controllerContent = str_replace("entity",lcfirst($controllerName),$controllerContent);
        $filesystem->dumpFile("BackBundle/Controller/".$controllerName."Controller.php", $controllerContent);
        $output->writeln("<fg=black;bg=green>You have success Generates admin controller,entity: {$entityName}</>");

    }

    protected function getControllerNameByEntityName($name)
    {
        $name = ucfirst($name);
        $names = explode('_', $name);
        $newName = '';
        if (count($names) > 1) {
            foreach ($names as $name) {
                $newName .= ucfirst($name);
            }
        } else {
            $newName = $name;
        }

        return $newName;
    }
}