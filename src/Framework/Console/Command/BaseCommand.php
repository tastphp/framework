<?php

namespace TastPHP\Framework\Console\Command;

use Symfony\Component\Console\Command\Command;

class BaseCommand extends Command
{
    protected function getControllerNameByEntityName($name)
    {
        return $this->processName($name);
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
            return $newName;
        }

        return $entityName;
    }

    protected function getGenerateEntityServiceNameByTableName($tableName)
    {
        return $this->processName($tableName);
    }

    protected function getQuestionHelper()
    {
        return $this->getHelper('question');
    }

    protected function changeDir($dir)
    {
        chdir(__BASEDIR__ . "/{$dir}");
    }

    protected function getTemplateDir()
    {
        return __IMPORT_DIR__ . "/Console/Command/Template";
    }

    private function processName($name)
    {
        list($name, $names) = $this->handleName($name);
        $newName = '';
        if (count($names) > 1) {
            foreach ($names as $name) {
                $newName .= ucfirst($name);
            }
            return $newName;
        }
        return $name;
    }

    private function handleName($name)
    {
        $name = ucfirst($name);
        $names = explode('_', $name);

        return [$name, $names];
    }
}