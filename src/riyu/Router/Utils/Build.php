<?php

namespace Riyu\Router\Utils;

class Build
{
    public function __construct(Storage $storage)
    {
        $this->storage = $storage;
    }

    public function build($uri, $callback, $method)
    {
        return $this->storage->addRoute($method, $uri, $callback);
    }

    public function group($callback)
    {
        $callback();
    }

    public function prefix($prefix)
    {
        $this->storage->addPrefix($prefix);
    }

    public function __call($name, $arguments)
    {
        $this->group(function () use ($name, $arguments) {
            $this->prefix($name);
            $this->build(...$arguments);
        });
    }

    public static function __callStatic($name, $arguments)
    {
        static::group(function () use ($name, $arguments) {
            static::prefix($name);
            static::build(...$arguments);
        });
    }
}
