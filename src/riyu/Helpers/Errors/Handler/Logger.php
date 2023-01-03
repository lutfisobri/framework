<?php
namespace Riyu\Helpers\Errors\Handler;

use Riyu\App\Config;
use Riyu\Helpers\Errors\Handler\Contract\Log;

class Logger implements Log
{
    private $datetime;
    private $type;
    private $message;
    private $file;
    private $line;
    private $path;

    public function __construct()
    {
        $path = Config::get('path');
        $this->path = $path . "storage/logs/";
    }

    public function write($errno, $errstr, $errfile, $errline)
    {
        $this->datetime()
            ->type($errno)
            ->message($errstr)
            ->file($errfile)
            ->line($errline)
            ->run();
    }

    public function run()
    {
        if ($this->validation()) {
            $this->save();
        }
    }

    private function validation()
    {
        if (empty($this->datetime) || is_null($this->datetime) || $this->datetime == '') {
            return false;
        }

        if (empty($this->type) || is_null($this->type) || $this->type == '') {
            return false;
        }

        if (empty($this->message) || is_null($this->message) || $this->message == '') {
            return false;
        }

        if (empty($this->file) || is_null($this->file) || $this->file == '') {
            return false;
        }

        if (empty($this->line) || is_null($this->line) || $this->line == '') {
            return false;
        }

        return true;
    }

    public function datetime()
    {
        $this->datetime = date('Y-m-d H:i:s');
        return $this;
    }

    public function type($type = 'warning')
    {
        if (is_int($type)) {
            $type = $this->getErrType($type);
        }
        $type = "PHP ". strtoupper($type);
        $this->type = $type;
        return $this;
    }

    public function getErrType($type)
    {
        switch ($type) {
            case E_ERROR:
                return 'error';
            case E_WARNING:
                return 'warning';
            case E_PARSE:
                return 'parse';
            case E_NOTICE:
                return 'notice';
            case E_CORE_ERROR:
                return 'core error';
            case E_CORE_WARNING:
                return 'core warning';
            case E_COMPILE_ERROR:
                return 'compile error';
            case E_COMPILE_WARNING:
                return 'compile warning';
            case E_USER_ERROR:
                return 'user error';
            case E_USER_WARNING:
                return 'user warning';
            case E_USER_NOTICE:
                return 'user notice';
            case E_STRICT:
                return 'strict';
            case E_RECOVERABLE_ERROR:
                return 'recoverable error';
            case E_DEPRECATED:
                return 'deprecated';
            case E_USER_DEPRECATED:
                return 'user deprecated';
            default:
                return 'unknown';
        }
    }

    public function message($message)
    {
        $this->message = $message;
        return $this;
    }

    public function file($file)
    {
        $this->file = $file;
        return $this;
    }

    public function line($line)
    {
        $this->line = "on line $line";
        return $this;
    }

    public function save()
    {
        $log = $this->datetime . ' ' . $this->type . ' ' . $this->message . ' ' . $this->file . ' ' . $this->line . PHP_EOL;
        $this->isDir();
        $filename = $this->path . 'log.log';
        file_put_contents($filename, $log, FILE_APPEND);
    }

    public function isDir()
    {
        if (!is_dir($this->path)) {
            mkdir($this->path, 0777, true);
        }
    }
}