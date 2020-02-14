<?php
declare(strict_types=1);


namespace Cratia\Rest\Dependencies;


use Cratia\Rest\Handlers\ShutdownHandler;
use Slim\App;
use StdClass;

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
     * @param $errno
     * @param $errstr
     * @param string $errfile
     * @param null $errline
     * @param array $errcontext
     * @return void
     */
    public static function registerError($errno, $errstr, $errfile = '', $errline = null, $errcontext = [])
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

        $s = new StdClass();
        $s->code = $strErrCode[$errno] . " ({$errno})";
        $s->message = $errstr;
        $s->file = $errfile;
        $s->line = $errline;
        $s->context = $errcontext;
        ErrorBag::getInstance()->attach($s);
    }

    /**
     * Log php errors to attach in output
     * @param App $app
     * @param int $error_types default E_ALL | E_STRICT
     */
    public function registerErrorHandler(App $app, $error_types = null)
    {
        $error_types = is_null($error_types) ? (E_ALL | E_STRICT) : $error_types;
        set_error_handler(['self', 'registerError'], $error_types);
    }

    /**
     * @param App $app
     * @return void
     */
    public function registerShutdownHandler(App $app)
    {
        $shutdownHandler = new ShutdownHandler($app->getContainer());
        register_shutdown_function($shutdownHandler);
    }
}