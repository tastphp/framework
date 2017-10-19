<?php

use PHPUnit\Framework\TestCase;

class KernelTest extends TestCase
{
    public function testInstance()
    {
        $this->assertEquals(is_object($this->getKernelInstance()), true);
        $this->assertEquals(is_object(\TastPHP\Framework\Kernel::getInstance()), true);
    }

    public function testSingleton()
    {
        $kernel = $this->getKernelInstance();
        $stdClass = new stdClass();
        $kernel->singleton('testkey', function () use ($stdClass) {
            return $stdClass;
        });

        $this->assertEquals($kernel['testkey'], $stdClass);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testNotMatchReplaceServiceProvider()
    {
        $key = 'Configs';
        $serviceProvider = 'TastPHP\Test\Config\ConfigServiceProvider';
        $kernel = $this->getKernelInstance();
        $kernel->replaceServiceProvider($key, $serviceProvider);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testNotMatchReplaceAlias()
    {
        $alias = 'Configs';
        $class = 'TastPHP\Test\Config\ConfigServiceProvider';
        $kernel = $this->getKernelInstance();
        $kernel->replaceAlias($alias, $class);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testNotMatchReplaceListener()
    {
        $eventName = 'app.test.request';
        $lisenter = 'TastPHP\Test\Listener\RequestListener@onTestRequestAction';
        $kernel = $this->getKernelInstance();
        $kernel->replaceListener($eventName, $lisenter);
    }

    protected function getKernelInstance()
    {
        return new \TastPHP\Framework\Kernel();
    }
}