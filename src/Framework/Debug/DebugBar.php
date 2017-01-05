<?php

namespace TastPHP\Framework\Debug;

class DebugBar extends \DebugBar\DebugBar
{
    public function __construct()
    {
        //$this->addCollector(new \DebugBar\DataCollector\PhpInfoCollector());
        //$this->addCollector(new \DebugBar\DataCollector\MessagesCollector());
        $this->addCollector(new \DebugBar\DataCollector\RequestDataCollector());
        $this->addCollector(new \DebugBar\DataCollector\TimeDataCollector());
        $this->addCollector(new \DebugBar\DataCollector\MemoryCollector());
    }
}