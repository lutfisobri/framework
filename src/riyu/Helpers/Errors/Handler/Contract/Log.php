<?php
namespace Riyu\Helpers\Errors\Handler\Contract;

interface Log
{
    public function datetime();
    public function type($type = 'warning');
    public function message($message);
    public function file($file);
    public function line($line);
    public function save();
}