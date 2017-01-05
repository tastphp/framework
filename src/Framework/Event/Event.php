<?php

namespace TastPHP\Framework\Event;

use Symfony\Component\EventDispatcher\Event as Symfony_Event;

class Event extends Symfony_Event
{
    protected $property;

    public function __construct($property = null)
    {
        $this->property = $property;
    }

    public function getProperty()
    {
        return $this->property;
    }

    public function setProperty($property)
    {
        $this->property = $property;
    }

}
