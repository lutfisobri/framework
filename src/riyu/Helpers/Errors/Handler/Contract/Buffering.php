<?php
namespace Riyu\Helpers\Errors\Handler\Contract;

interface Buffering
{
    public function startOb();
    public function endOb();
    public function getOb();
    public function cleanOb();
    public function flushOb();
    public function obStatus();
    public function obGetLevel();
}