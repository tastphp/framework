<?php
require __DIR__ . '/../vendor/autoload.php';

define('__BASEDIR__', __DIR__ . '/..');

$kernel = new \TastPHP\Framework\Kernel();
dump($kernel);exit();
$kernel->run();