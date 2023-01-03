<?php
namespace Riyu\Helpers\Errors\Handler;

use Riyu\App\Config;

class Run
{
    protected $system;
    protected $view;

    public function __construct()
    {
        $this->system = new System();
        $this->view = new View();
    }

    public function run()
    {
        $this->running();
    }

    private function running()
    {
        $this->system->register($this->isSafety(), $this->isDebug());
    }

    private function isSafety()
    {
        return Config::get('app')['safety'];
    }

    private function isDebug()
    {
        return Config::get('app')['debug'];
    }

    public function __destruct()
    {
        $this->view->endOb();
    }

    public function getOb()
    {
        return $this->view->getOb();
    }

    public function cleanOb()
    {
        $this->view->cleanOb();
    }

    public function flushOb()
    {
        $this->view->flushOb();
    }
}