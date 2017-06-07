<?php

namespace TastPHP\Framework\Console\Command;

use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Filesystem\Filesystem;

class GenerateEntityServiceCommand extends BaseCommand
{
    protected function configure()
    {
        $this
            ->setName('generate:entityService')
            ->setDescription('Generates a entityService(Dao)')
            ->addArgument(
                'name',
                InputArgument::OPTIONAL,
                'Entity name (table name,default:Demo)'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->changeDir('src/');
        $name = $input->getArgument('name');
        if ($name) {
            $this->generateEntityService($output, $name);
            return;
        }
        $helper = $this->getHelper('question');
        $question = new Question('Please enter the name of the Entity(table name):', 'Demo');
        $name = $helper->ask($input, $output, $question);
        $this->generateEntityService($output, $name);

    }

    private function generateEntityService($output, $name)
    {
        $tableName = $name;
        $name = $this->getGenerateEntityServiceNameByTableName($tableName);
        $filesystem = new Filesystem();
        //service file
        $filesystem->mkdir("Service/$name");
        $serviceContent = file_get_contents(__DIR__ . "/Template/EntityService.txt");
        $serviceContent = str_replace('Entity', $name, $serviceContent);
        $serviceContent = str_replace('entity', lcfirst($name), $serviceContent);
        $filesystem->dumpFile("Service/$name/{$name}Service.php", $serviceContent);
        //service Impl file
        $filesystem->mkdir("Service/$name/Impl");
        $serviceImplContent = file_get_contents(__DIR__ . "/Template/EntityServiceImpl.txt");
        $serviceImplContent = str_replace('Entity', $name, $serviceImplContent);
        $serviceImplContent = str_replace('entity', lcfirst($name), $serviceImplContent);
        $filesystem->dumpFile("Service/$name/Impl/{$name}ServiceImpl.php", $serviceImplContent);
        //Dao file
        $filesystem->mkdir("Service/$name/Dao");
        $daoContent = file_get_contents(__DIR__ . "/Template/EntityDao.txt");
        $daoContent = str_replace('Entity', $name, $daoContent);
        $daoContent = str_replace('entity', lcfirst($name), $daoContent);
        $filesystem->dumpFile("Service/$name/Dao/{$name}Dao.php", $daoContent);
        //Dao Impl file
        $filesystem->mkdir("Service/$name/Dao/Impl");
        $daoImplContent = file_get_contents(__DIR__ . "/Template/EntityDaoImpl.txt");
        $daoImplContent = str_replace('Entity', $name, $daoImplContent);
        $daoImplContent = str_replace('entity', lcfirst($name), $daoImplContent);
        $daoImplContent = str_replace('{{tableName}}', $tableName, $daoImplContent);
        $filesystem->dumpFile("Service/$name/Dao/Impl/{$name}DaoImpl.php", $daoImplContent);
        $output->writeln("<fg=black;bg=green>You have success generate {$name}Service(Dao)</>");
    }
}