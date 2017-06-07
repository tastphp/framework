<?php

namespace TastPHP\Framework\Console\Command;

use Symfony\Component\Console\Command\Command;

class BaseCommand extends Command
{
    protected function getControllerNameByEntityName($name)
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
        list($serviceName, $names) = $this->handleName($tableName);
        $nameTemp = '';
        if (count($names) > 1) {
            foreach ($names as $tableName) {
                $nameTemp .= ucfirst($tableName);
            }
            return $nameTemp;
        }
        return $serviceName;
    }

    protected function getQuestionHelper()
    {
        return $this->getHelper('question');
    }

    protected function changeDir($dir)
    {
        chdir(__BASEDIR__ . "/{$dir}");
    }

    private function handleName($name)
    {
        $name = ucfirst($name);
        $names = explode('_', $name);

        return [$name, $names];
    }
}