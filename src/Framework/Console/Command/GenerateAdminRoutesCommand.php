<?php
namespace TastPHP\Framework\Console\Command;

use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Filesystem\Filesystem;

class GenerateAdminRoutesCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('generate:adminRoutes')
            ->setDescription('Generates a admin routes');

    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        chdir(__BASEDIR__ . '/src/');

        $helper = $this->getHelper('question');

        $question = new Question('Please enter the name of entity(default:demo_test)', 'demo_test');
        $entityName = $helper->ask($input, $output, $question);

        $routeEntityName = $this->getRouteEntityNameByEntityName($entityName);
        $controllerName = $this->getControllerNameByEntityName($entityName);

        $filesystem = new Filesystem();
        $routesContent = file_get_contents("BackBundle/Config/routes.yml");
        $newRouteContent = file_get_contents("App/Console/Command/Template/adminRoutes.txt");
        $newRouteContent = str_replace('{{entityKey}}', $entityName, $newRouteContent);
        $newRouteContent = str_replace('entity', $routeEntityName, $newRouteContent);
        $newRouteContent = str_replace('Entity', $controllerName, $newRouteContent);
        $routesContent = $routesContent . "\r\n" . $newRouteContent;
        $filesystem->dumpFile("BackBundle/Config/routes.yml", $routesContent);
        $output->writeln("<fg=black;bg=green>You have success Generates admin routes,entity: {$entityName}</>");

    }

    protected function getRouteEntityNameByEntityName($entityName)
    {
        $names = explode('_', $entityName);

        $newName = '';
        if (count($names) > 1) {
            foreach ($names as $name) {
                $newName .= '/' . $name;
            }
            $newName = substr($newName, 1, strlen($newName));
        } else {
            $newName = $entityName;
        }

        return $newName;
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

    protected function getQuestionHelper()
    {
        return $this->getHelper('question');
    }

}