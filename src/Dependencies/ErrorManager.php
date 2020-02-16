<?php
declare(strict_types=1);


namespace Cratia\Rest\Dependencies;


use Cratia\Rest\Handlers\ShutdownHandler;
use Psr\Container\ContainerInterface;
use stdClass;

/**
 * Class ErrorManager
 * @package Cratia\Rest\Dependencies
 */
class ErrorManager
{
    /**
     * @var self
     */
    private static $_instance;

    /**
     * self constructor.
     */
    private function __construct()
    {
    }

    /**
     * @return self
     */
    public static function getInstance(): self
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * @return ErrorBag
     */
    public function getBag(): ErrorBag
    {
        return ErrorBag::getInstance();
    }

    /**
     * Callback for error handling
     * @param int $errno
     * @param string $errstr
     * @param string $errfile
     * @param int $errline
     * @param array $errcontext
     * @return void
     */
    public function registerError(int $errno, string $errstr, string $errfile = null, int $errline = null, array $errcontext = null)
    {
        $strErrCode = [
            1 => "E_ERROR",
            2 => "E_WARNING",
            4 => "E_PARSE",
            8 => "E_NOTICE",
            16 => "E_CORE_ERROR",
            32 => "E_CORE_WARNING",
            64 => "E_COMPILE_ERROR",
            128 => "E_COMPILE_WARNING",
            256 => "E_USER_ERROR",
            512 => "E_USER_WARNING",
            1024 => "E_USER_NOTICE",
            2048 => "E_STRICT",
            4096 => "E_RECOVERABLE_ERROR",
            8192 => "E_DEPRECATED",
            16384 => "E_USER_DEPRECATED",
            32767 => "E_ALL"
        ];

        $s = new stdClass();
        $s->code = $strErrCode[$errno] . " ({$errno})";
        $s->message = $errstr;
        $s->file = $errfile;
        $s->line = $errline;
        $s->context = $errcontext;
        ErrorBag::getInstance()->attach($s);
    }

    /**
     * Log php errors to attach in output
     * @param ContainerInterface $container
     * @param int $error_types default E_ALL | E_STRICT
     */
    public function registerErrorHandler(ContainerInterface $container, $error_types = null)
    {
        $error_types = is_null($error_types) ? (E_ALL | E_STRICT) : $error_types;
        set_error_handler(
            function (int $errno, string $errstr, string $errfile = null, int $errline = null, array $errcontext = null) {
                $this->registerError($errno, $errstr, $errfile, $errline, $errcontext);
            }, $error_types);
    }

    /**
     * @param ContainerInterface $container
     * @return void
     */
    public function registerShutdownHandler(ContainerInterface $container)
    {
        $shutdownHandler = new ShutdownHandler($container);
        register_shutdown_function($shutdownHandler);
    }
}