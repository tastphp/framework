<?php

namespace TastPHP\Framework\Console\Command;

use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Filesystem\Filesystem;

class GenerateEntityServiceCommand extends Command
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
        chdir(__BASEDIR__ . '/src/');
        $name = $input->getArgument('name');
        if ($name) {
            $this->generateEntityService($output, $name);
            exit();
        }
        $helper = $this->getHelper('question');
        $question = new Question('Please enter the name of the Entity(table name):', 'Demo');
        $name = $helper->ask($input, $output, $question);
        $this->generateEntityService($output, $name);

    }


    private function generateEntityService($output, $name)
    {
        $tableName = $name;
        $name = ucfirst($name);
        $names = explode('_',$name);
        $newName = '';
        if (count($names) > 1) {
            foreach($names as $name)
            {
                $newName .= ucfirst($name);
            }
        } else {
            $newName = $name;
        }

        $name = $newName;
        $filesystem = new Filesystem();
        //service file
        $filesystem->mkdir("Service/$name");
        $entityServiceContent = file_get_contents(__DIR__ . "/Template/EntityService.txt");
        $entityServiceContent = str_replace('Entity', $name, $entityServiceContent);
        $entityServiceContent = str_replace('entity', lcfirst($name), $entityServiceContent);
        $filesystem->dumpFile("Service/$name/{$name}Service.php", $entityServiceContent);
        //service Impl file
        $filesystem->mkdir("Service/$name/Impl");
        $entityServiceImplContent = file_get_contents(__DIR__ . "/Template/EntityServiceImpl.txt");
        $entityServiceImplContent = str_replace('Entity', $name, $entityServiceImplContent);
        $entityServiceImplContent = str_replace('entity', lcfirst($name), $entityServiceImplContent);
        $filesystem->dumpFile("Service/$name/Impl/{$name}ServiceImpl.php", $entityServiceImplContent);
        //Dao file
        $filesystem->mkdir("Service/$name/Dao");
        $entityDaoContent = file_get_contents(__DIR__ . "/Template/EntityDao.txt");
        $entityDaoContent = str_replace('Entity', $name, $entityDaoContent);
        $entityDaoContent = str_replace('entity', lcfirst($name), $entityDaoContent);
        $filesystem->dumpFile("Service/$name/Dao/{$name}Dao.php", $entityDaoContent);
        //Dao Impl file
        $filesystem->mkdir("Service/$name/Dao/Impl");
        $entityDaoImplContent = file_get_contents(__DIR__ . "/Template/EntityDaoImpl.txt");
        $entityDaoImplContent = str_replace('Entity', $name, $entityDaoImplContent);
        $entityDaoImplContent = str_replace('entity', lcfirst($name), $entityDaoImplContent);
        $entityDaoImplContent = str_replace('{{tableName}}', $tableName, $entityDaoImplContent);
        $filesystem->dumpFile("Service/$name/Dao/Impl/{$name}DaoImpl.php", $entityDaoImplContent);
        $output->writeln("<fg=black;bg=green>You have success generate {$name}Service(Dao)</>");
    }

    protected function getQuestionHelper()
    {
        return $this->getHelper('question');
    }
}