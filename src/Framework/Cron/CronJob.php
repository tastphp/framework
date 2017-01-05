<?php

namespace TastPHP\Framework\Cron;

abstract class CronJob
{
    abstract function handle();
}