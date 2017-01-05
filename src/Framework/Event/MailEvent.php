<?php
namespace TastPHP\Framework\Event;

use Symfony\Component\EventDispatcher\Event as Symfony_Event;

class MailEvent extends Symfony_Event
{
    const MAIlSEND = "mail.send";

    private $parameters;

    public function __construct($parameters = [])
    {
        $this->parameters = $parameters;
    }

    function __set($name, $value)
    {
        $this->$name = $value;
    }

    function __get($name)
    {
        return isset($this->$name) ? $this->$name : null;
    }
}