<?php

namespace TastPHP\Framework\Logger;

use Monolog\Handler\StreamHandler;
use Monolog\Logger as Monolog;

/**
 * Class LoggerService https://github.com/Seldaek/monolog/blob/master/doc/01-usage.md#log-levels
 * @package TastPHP\Framework\Logger
 */
class LoggerService
{
    public static $cacheDir = "var/logs";

    /**
     * Detailed debug information.
     * @param $message
     * @param $context
     * @param string $channel
     */
    public static function debug($message, $context = [], $channel = 'tastphp.logger')
    {

        $logger = static::getLoggerByLevelAndChannel(Monolog::DEBUG, $channel);
        $logger->addDebug($message, $context);
    }

    /**
     *Interesting events. Examples: User logs in, SQL logs.»
     * @param $message
     * @param $context
     * @param string $channel
     */
    public static function info($message, $context = [], $channel = 'tastphp.logger')
    {
        $logger = static::getLoggerByLevelAndChannel(Monolog::INFO, $channel);
        $logger->addInfo($message, $context);
    }

    /**
     * Normal but significant events.
     * @param $message
     * @param $context
     * @param string $channel
     */
    public static function notice($message, $context = [], $channel = 'tastphp.logger')
    {
        $logger = static::getLoggerByLevelAndChannel(Monolog::NOTICE, $channel);
        $logger->addNotice($message, $context);
    }

    /**
     * Exceptional occurrences that are not errors. Examples: Use of deprecated APIs, poor use of an API, undesirable things that are not necessarily wrong.
     * @param $message
     * @param $context
     * @param string $channel
     */
    public static function warning($message, $context = [], $channel = 'tastphp.logger')
    {
        $logger = static::getLoggerByLevelAndChannel(Monolog::WARNING, $channel);
        $logger->addWarning($message, $context);
    }

    /**
     *  Runtime errors that do not require immediate action but should typically be logged and monitored.
     * @param $message
     * @param $context
     * @param string $channel
     */
    public static function error($message, $context = [], $channel = 'tastphp.logger')
    {
        $logger = static::getLoggerByLevelAndChannel(Monolog::ERROR, $channel);
        $logger->addError($message, $context);
    }

    /**
     * Critical conditions. Example: Application component unavailable, unexpected exception.
     * @param $message
     * @param $context
     * @param string $channel
     */
    public static function critical($message, $context = [], $channel = 'tastphp.logger')
    {
        $logger = static::getLoggerByLevelAndChannel(Monolog::CRITICAL, $channel);
        $logger->addCritical($message, $context);
    }

    /**
     * Action must be taken immediately. Example: Entire website down, database unavailable, etc. This should trigger the SMS alerts and wake you up.
     * @param $message
     * @param $context
     * @param string $channel
     */
    public static function alert($message, $context = [], $channel = 'tastphp.logger')
    {
        $logger = static::getLoggerByLevelAndChannel(Monolog::ALERT, $channel);
        $logger->addAlert($message, $context);
    }

    /**
     *  system is unusable.
     * @param $message
     * @param $context
     * @param string $channel
     */
    public static function emergency($message, $context = [], $channel = 'tastphp.logger')
    {
        $logger = static::getLoggerByLevelAndChannel(Monolog::ALERT, $channel);
        $logger->addEmergency($message, $context);
    }

    /**
     * @param $level
     * @param $channel
     * @return Monolog
     */
    protected static function getLoggerByLevelAndChannel($level, $channel)
    {
        $levelName = Monolog::getLevelName($level);
        $cacheDir = static::$cacheDir;
        $stream = __BASEDIR__ . "/" . $cacheDir . "/" . strtolower($levelName) . ".log";
        return new Monolog($channel, [new StreamHandler($stream, $level)]);
    }

    public static function setDir($dir)
    {
        static::$cacheDir = $dir;
    }
}