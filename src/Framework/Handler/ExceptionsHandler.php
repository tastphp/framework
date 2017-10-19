<?php

namespace TastPHP\Framework\Handler;

use TastPHP\Framework\Event\AppEvent;
use TastPHP\Framework\Event\ExceptionEvent;
use TastPHP\Framework\Event\KernalEvent;
use TastPHP\Framework\Event\MailEvent;
use TastPHP\Framework\Kernel;

class ExceptionsHandler
{
    protected $container;

    private $levels = array(
        E_WARNING => 'Warning',
        E_NOTICE => 'Notice',
        E_USER_ERROR => 'User Error',
        E_USER_WARNING => 'User Warning',
        E_USER_NOTICE => 'User Notice',
        E_STRICT => 'Runtime Notice',
        E_RECOVERABLE_ERROR => 'Catchable Fatal Error',
        E_DEPRECATED => 'Deprecated',
        E_USER_DEPRECATED => 'User Deprecated',
        E_ERROR => 'Error',
        E_CORE_ERROR => 'Core Error',
        E_COMPILE_ERROR => 'Compile Error',
        E_PARSE => 'Parse',
        0 => 'exception'
    );

    public function bootstrap()
    {
        $this->container = Kernel::getInstance();

        error_reporting(E_ALL);

        set_error_handler([$this, 'handleError']);

        set_exception_handler([$this, 'handleException']);

        register_shutdown_function([$this, 'handleShutdown']);

        ini_set('display_errors', 'Off');
    }

    /**
     * Convert a PHP error to an ErrorException.
     *
     * @param  int $level
     * @param  string $message
     * @param  string $file
     * @param  int $line
     * @return void
     */
    public function handleError($level, $message, $file = '', $line = 0)
    {
        if (error_reporting() & $level) {
            $error = [
                'message' => $message,
                'file' => $file,
                'line' => $line,
                'type' => $level,
            ];

            switch ($level) {
                case E_USER_ERROR:
                    $this->record($error);
                    if ($this->container->runningInConsole()) {
                        $this->renderForConsole($error);
                    }

                    if (!$this->container->runningInConsole()) {
                        $this->renderHttpResponse($error);
                    }
                    break;
                default:
                    $this->record($error, 'warning');
                    break;
            }
            return true;
        }

        return false;
    }

    public function handleException($exception)
    {
        $error = [
            'message' => $exception->getMessage(),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'trace' => $exception->getTraceAsString(),
            'type' => $exception->getCode(),
        ];

        if ($this->isFatal($error['type'])) {
            $this->record($error);
        }

        if ($this->container->runningInConsole()) {
            $this->renderForConsole($error);
        }

        if (!$this->container->runningInConsole()) {
            $this->renderHttpResponse($error);
        }
    }

    protected function renderForConsole($e)
    {

    }

    /**
     * Render an exception as an HTTP response and send it.
     *
     * @param  \Exception $e
     * @return void
     */
    protected function renderHttpResponse($e)
    {
        $error = '';
        $errorArray = [];

        if ($this->container['env'] != 'prod') {
            $error = $e;
            if (!is_array($error)) {
                $trace = debug_backtrace();
                $errorArray['message'] = $e;
                $errorArray['file'] = $trace[0]['file'];
                $errorArray['line'] = $trace[0]['line'];
                ob_start();
                debug_print_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
                $e['trace'] = ob_get_clean();
                $error = $errorArray;
            }

            $this->container['eventDispatcher']->dispatch(AppEvent::EXCEPTION, new ExceptionEvent($error, $this->container));
        }

        if ($this->container['env'] == 'prod') {
            $this->container['eventDispatcher']->dispatch(AppEvent::EXCEPTION, new ExceptionEvent($e, $this->container));
        }
    }

    /**
     * Handle the PHP shutdown event.
     *
     * @return void
     */
    public function handleShutdown()
    {
        if ($exception = error_get_last()) {
            if ($this->isFatal($exception['type'])) {
                $this->record($exception);
                $exception['trace'] = '';
                if ($this->container->runningInConsole()) {
                    $this->renderForConsole($exception);
                }

                if (!$this->container->runningInConsole()) {
                    $this->renderHttpResponse($exception);
                }
            }
        }
    }

    protected function record($exception, $type = 'error', $context = array())
    {
        $env = $this->container['env'];
        $node = $this->container['name'];
        $body = 'App Env: 【' . $env . '】, node: 【' . $node . '】 <br> [ Exception ' . $exception['message'] . '[' . $exception['file'] . ' : ' . $exception['line'] . ']';

        if (($this->container['swift.mail.enabled'] == 'on') && (!$this->container['debug'])) {
            $this->container['eventDispatcher']->dispatch(MailEvent::MAIlSEND, new MailEvent($body));
        }
        \Logger::$type('[' . $this->levels[$exception['type']] . '] ' . $exception['message'] . '[' . $exception['file'] . ' : ' . $exception['line'] . ']', $context);
    }

    /**
     * Determine if the error type is fatal.
     *
     * @param  int $type
     * @return bool
     */
    protected function isFatal($type)
    {
        return in_array($type, [E_ERROR, E_CORE_ERROR, E_COMPILE_ERROR, E_PARSE]);
    }

}