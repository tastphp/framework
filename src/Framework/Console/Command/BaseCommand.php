<?php

namespace TastPHP\Framework\Console\Command;

use Symfony\Component\Console\Command\Command;

class BaseCommand extends Command
{
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

    protected function getGenerateEntityServiceNameByTableName($tableName)
    {
        $entityServiceName = ucfirst($tableName);
        $names = explode('_',$tableName);
        $entityServiceNameTemp = '';
        if (count($names) > 1) {
            foreach($names as $tableName)
            {
                $entityServiceNameTemp .= ucfirst($tableName);
            }
        } else {
            $entityServiceNameTemp = $entityServiceName;
        }

        return $entityServiceNameTemp;
    }

    protected function getQuestionHelper()
    {
        return $this->getHelper('question');
    }
}