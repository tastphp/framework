<?php

namespace TastPHP\Framework\Config;

use TastPHP\Framework\Container\Container;

/**
 * Class ConfigService
 * @package TastPHP\Framework\Config
 */
class ConfigService
{
    protected $app;

    public function __construct(Container $app)
    {
        $this->app = $app;
    }

    public function register()
    {
        $configs = \Yaml::parse(file_get_contents(__BASEDIR__ . '/config/config.yml'));
        $this->registerAppConfig($configs);
        date_default_timezone_set($this->app['timezone']);
        $this->registerBusinessConfig($configs);
    }

    public function parseResource($resource, $isCustom = false)
    {
        $config = [];
        if (true == $isCustom) {
            $config = \Yaml::parse(file_get_contents(__BASEDIR__ . "/src/{$resource}"));
        }

        if (false == $isCustom) {
            $config['version'] = APP_VERSION;
            $config['debug'] = true;
            $config['secret'] = 'tastphp';
            $config['env'] = 'tastphp';
            $config['timezone'] = 'UTC';
            $config['name'] = 'tastphp';

            if (file_exists(__BASEDIR__ . "/config/{$resource}")) {
                $config = \Yaml::parse(file_get_contents(__BASEDIR__ . "/config/{$resource}"));
            }
        }

        if (!$config) {
            if (isset($default)) return $default;
            throw new \Exception("Can not found resource {$resource} file,please check it");
        }

        return $config;
    }

    public function parse($serviceName, $default = [])
    {
        $config = [];
        $serviceName = strtolower($serviceName);

        if (file_exists(__BASEDIR__ . "/config/{$serviceName}.yml")) {
            $config = \Yaml::parse(file_get_contents(__BASEDIR__ . "/config/{$serviceName}.yml"));
        }

        if (!$config) {
            if (isset($default)) {
                return $default;
            }
            throw new \Exception("Can not found {$serviceName} config file,please check it");
        }

        return $config;
    }

    public function inject($serviceName, $hasPrefix = false)
    {
        $this->set($this->parse($serviceName), $serviceName, $hasPrefix);
    }

    public function injectResource($resource, $serviceName, $hasPrefix = false, $isCustom = false)
    {
        $this->set($this->parseResource($resource, $isCustom), $serviceName, $hasPrefix);
    }

    public function get($serviceName, $key)
    {
        $config = \Yaml::parse(file_get_contents(__BASEDIR__ . "/config/{$serviceName}.yml"));
        if (!$config) {
            throw new \Exception("Can not found {$serviceName} config file,please check it");
        }

        $key = $serviceName . "." . $key;

        if (empty($this->app[$key])) {
            throw new \Exception("Can not found key : {$key} in app container");
        }

        return $this->app[$key];
    }

    public function check($serviceName)
    {
        $serviceName = strtolower($serviceName);
        if (!file_exists(__BASEDIR__ . "/config/{$serviceName}.yml")) {
            return false;
        }

        return true;
    }

    public function set(array $values, $serviceName, $hasPrefix = '')
    {
        foreach ($values as $key => $value) {
            if ($hasPrefix) {
                $key = $serviceName . '.' . $key;
            }
            $this->app[$key] = $value;
        }

        return $this->app;
    }

    private function registerAppConfig($configs)
    {
        $configImports = $configs['imports'];
        foreach ($configImports as $configImport) {
            if (empty($configImport['resource']) || empty($configImport['name'])) {
                throw new \Exception("Can not found key `resource` and `name` in imports config,please check it");
            }
            $this->injectResource($configImport['resource'], $configImport['name']);
        }
    }

    private function registerBusinessConfig($configs)
    {
        $businessConfigs = $configs['business_config'];
        foreach ($businessConfigs as $businessConfig) {
            if (empty($businessConfig['resource'])) {
                throw new \Exception("Can not found key `resource` in business_config,please check it");
            }
            $resource = $businessConfig['resource'];
            if (is_file(__BASEDIR__ . "/src/" . $resource) && file_exists(__BASEDIR__ . "/src/" . $resource)) {
                $this->injectResource($resource, '', false, true);
            }
        }
    }
}