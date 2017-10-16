<?php

namespace TastPHP\Framework\Config;

use Symfony\Component\Yaml\Yaml;

/**
 * Class YamlService
 * @package TastPHP\Framework\Config
 */
class YamlService
{
    /**
     * @param string $input A string containing YAML
     * @return mixed
     */
    public static function parse($input)
    {
        if (extension_loaded("yaml")) {
            return \yaml_parse($input);
        } else {
            return Yaml::parse($input);
        }
    }
}