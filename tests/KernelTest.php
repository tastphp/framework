<?php

class KernelTest extends PHPUnit_Framework_TestCase
{
    public function testInstance()
    {
        $this->assertEquals(is_object($this->getKernelInstance()), true);
        $this->assertEquals(is_object($this->getKernelMinInstance()), true);
        $this->assertEquals(is_object(\TastPHP\Framework\Kernel::getInstance()), true);
        $this->assertEquals(is_object(\TastPHP\Framework\KernelMin::getInstance()), true);
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
     * @expectedException     Exception
     */
    public function testNotMatchReplaceServiceProvider()
    {
        $kernel = $this->getKernelInstance();
        $kernel->replaceServiceProvider("Configs", 'TastPHP\Test\Config\ConfigServiceProvider');
        $kernel = $this->getKernelMinInstance();
        $kernel->replaceServiceProvider("Configs", 'TastPHP\Test\Config\ConfigServiceProvider');
    }

    /**
     * @expectedException     Exception
     */
    public function testNotMatchReplaceAlias()
    {
        $kernel = $this->getKernelInstance();
        $kernel->replaceAlias("Configs", 'TastPHP\Test\Config\ConfigServiceProvider');
        $kernel = $this->getKernelMinInstance();
        $kernel->replaceAlias("Configs", 'TastPHP\Test\Config\ConfigServiceProvider');
    }

    protected function getKernelInstance()
    {
        return new \TastPHP\Framework\Kernel();
    }

    protected function getKernelMinInstance()
    {
        return new \TastPHP\Framework\KernelMin();
    }
}