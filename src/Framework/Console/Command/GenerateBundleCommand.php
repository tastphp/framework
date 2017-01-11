<?php
namespace TastPHP\Framework\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Filesystem\Filesystem;

class GenerateBundleCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('generate:bundle')
            ->setDescription('Generates a bundle')
            ->addArgument(
                'name',
                InputArgument::OPTIONAL,
                'Entity name (default:Demo)'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        chdir(__BASEDIR__ . '/src/');
        $name = $input->getArgument('name');
        if ($name) {
            $this->generateBundleService($output, $name);
            exit();
        }
        $helper = $this->getHelper('question');
        $question = new Question('Please enter the name of the bundle:', 'DemoBundle');
        $name = $helper->ask($input, $output, $question);
        $this->generateBundleService($output, $name);
    }

    private function generateBundleService($output, $name)
    {
        if ('Bundle' !== substr($name, -6)) {
            throw new \RuntimeException(
                'The name of the bundle should be suffixed with \'Bundle\''
            );
        }
        $name = ucfirst($name);
        $this->generateBundleDir($name);
        $this->updateRoutesConfig($name);
        $this->updateConfig($name);
        $output->writeln("<fg=black;bg=green>You have success generate {$name}Bundle</>");
    }

    private function generateBundleDir($name)
    {
        $fs = new Filesystem();
        $fs->mkdir("{$name}/Config");
        $fs->dumpFile("{$name}/Config/routes.yml", '');
        $fs->mkdir("{$name}/Controller");
        $fs->dumpFile("{$name}/Controller/.gitkeep", '');
        $fs->mkdir("{$name}/Listener");
        $fs->dumpFile("{$name}/Listener/.gitkeep", '');
    }

    private function updateRoutesConfig($name)
    {
        $routesConfigContent = file_get_contents(__DIR__ . "/Template/routesConfig.txt");
        $routesConfigContent = str_replace('{{demo}}', $name, $routesConfigContent);
        file_put_contents(__BASEDIR__ . "/config/routes.yml", PHP_EOL . $routesConfigContent, FILE_APPEND);
    }

    private function updateConfig($name)
    {
        $configContent = file_get_contents(__DIR__ . "/Template/config.txt");
        $configContent = str_replace('{{Demo}}', $name, $configContent);
        $configContent = str_replace('{{demo}}', lcfirst($name), $configContent);
        file_put_contents(__BASEDIR__ . "/config/config.yml", PHP_EOL . $configContent, FILE_APPEND);
    }

    protected function getQuestionHelper()
    {
        $helper = $this->getHelper('question');
        return $helper;
    }
}