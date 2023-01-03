<?php
namespace Riyu\Helpers\Errors\Handler;

use Riyu\Helpers\Errors\Handler\Contract\System as ContractSystem;

class System implements ContractSystem
{
    private $isSafety;

    private $isDebug;

    public function register($safety = false, $debug = false)
    {
        $this->isSafety = $safety;
        $this->isDebug = $debug;
        set_error_handler([$this, 'handle']);
        if ($this->isDebug) {
            set_exception_handler([$this, 'exception']);
            register_shutdown_function([$this, 'shutdown']);
        }
    }

    public function handle($errno, $errstr, $errfile, $errline)
    {
        $this->errorLog($errno, $errstr, $errfile, $errline);
        if ($this->isSafety) {
            throw new \ErrorException($errstr, 0, $errno, $errfile, $errline);
        }
    }

    public function shutdown()
    {
        $error = error_get_last();
        if ($error) {
            $this->handle($error['type'], $error['message'], $error['file'], $error['line']);
        }
    }

    public function exception($exception)
    {
        $this->unregister();
        $this->render($exception);
    }

    public function render($exception)
    {
        $view = new View();
        $view->render($exception, $this->isSafety);
    }

    public function unregister()
    {
        restore_error_handler();
        restore_exception_handler();
    }

    public function errorLog($errno, $errstr, $errfile, $errline)
    {
        $log = new Logger();
        $log->write($errno, $errstr, $errfile, $errline);
    }
}
