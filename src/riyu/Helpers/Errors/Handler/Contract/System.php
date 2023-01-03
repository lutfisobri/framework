<?php
namespace Riyu\Helpers\Errors\Handler\Contract;

interface System
{
    public function register();
    public function handle($errno, $errstr, $errfile, $errline);
    public function shutdown();
    public function exception($exception);
}