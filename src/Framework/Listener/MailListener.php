<?php
namespace TastPHP\Framework\Listener;

use TastPHP\Framework\Event\MailEvent;

class MailListener
{
    public function onSendMailAction(MailEvent $event)
    {
        $data = $event->parameters;

        \Queue::put(\Kernel::getInstance()->singleton('tube'), json_encode($data));
    }
}