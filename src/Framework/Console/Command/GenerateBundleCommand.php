<?php

namespace TastPHP\Framework\Console\Command;

use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Filesystem\Filesystem;

class GenerateBundleCommand extends BaseCommand
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
        $name = $input->getArgument('name');

        if ($name) {
            $this->generateBundleService($output, $name);
            return;
        }

        $helper = $this->getHelper('question');
        $question = new Question('Please enter the name of the bundle (default:DemoBundle):', 'DemoBundle');
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
        $templateDir = $this->getTemplateDir();
        $bundleDir = __EXPORT_DIR__ . "/src/" . $name;
        $this->generateBundleDir($bundleDir);
        $this->updateRoutesConfig($name, $templateDir);
        $this->updateConfig($name, $templateDir);
        $output->writeln("<fg=black;bg=green>You have success generate {$name}Bundle</>");
    }

    private function generateBundleDir($bundleDir)
    {
        $filesystem = new Filesystem();
        $filesystem->mkdir("{$bundleDir}/Config");
        $filesystem->dumpFile("{$bundleDir}/Config/routes.yml", '');
        $filesystem->mkdir("{$bundleDir}/Controller");
        $filesystem->dumpFile("{$bundleDir}/Controller/.gitkeep", '');
        $filesystem->mkdir("{$bundleDir}/Listener");
        $filesystem->dumpFile("{$bundleDir}/Listener/.gitkeep", '');
    }

    private function updateRoutesConfig($name, $templateDir)
    {
        $routesConfigContent = file_get_contents($templateDir . "/routesConfig.txt");
        $routesConfigContent = str_replace('{{demo}}', $name, $routesConfigContent);
        file_put_contents(__EXPORT_DIR__ . "/config/routes.yml", PHP_EOL . $routesConfigContent, FILE_APPEND);
    }

    private function updateConfig($name, $templateDir)
    {
        $configContent = file_get_contents($templateDir . "/config.txt");
        $configContent = str_replace('{{Demo}}', $name, $configContent);
        $configContent = str_replace('{{demo}}', lcfirst($name), $configContent);
        file_put_contents(__EXPORT_DIR__ . "/config/config.yml", PHP_EOL . $configContent, FILE_APPEND);
    }
}