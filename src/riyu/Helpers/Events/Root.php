<?php

namespace Riyu\Helpers\Events;

class Root
{
    public $dir;

    public static function set($dir)
    {
        $root = new self;
        $root->dir = $dir;
        return $root;
    }

    public static function get()
    {
        return (new self)->dir;
    }
}
