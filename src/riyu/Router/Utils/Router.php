<?php

namespace Riyu\Router\Utils;

use Riyu\Router\Utils\Storage;

abstract class Router
{
    private static $prefix = "";

    public static function get($uri, $callback)
    {
        if (self::$prefix !== "") {
            ($uri == "/") ? $uri = self::$prefix : $uri = self::$prefix . $uri;
        }
        Storage::addRoute("GET", $uri, $callback);
    }

    public static function post($uri, $callback)
    {
        if (self::$prefix !== "") {
            ($uri == "/") ? $uri = self::$prefix : $uri = self::$prefix . $uri;
        }
        Storage::addRoute("POST", $uri, $callback);
    }

    public static function put($uri, $callback)
    {
        if (self::$prefix !== "") {
            ($uri == "/") ? $uri = self::$prefix : $uri = self::$prefix . $uri;
        }
        Storage::addRoute("PUT", $uri, $callback);
    }

    public static function delete($uri, $callback)
    {
        if (self::$prefix !== "") {
            ($uri == "/") ? $uri = self::$prefix : $uri = self::$prefix . $uri;
        }
        Storage::addRoute("DELETE", $uri, $callback);
    }

    public static function patch($uri, $callback)
    {
        if (self::$prefix !== "") {
            ($uri == "/") ? $uri = self::$prefix : $uri = self::$prefix . $uri;
        }
        Storage::addRoute("PATCH", $uri,$callback);
    }

    public static function group($prefix, $callback)
    {
        self::$prefix = $prefix;
        $callback(new static);
        self::$prefix = "";
    }

    public static function prefix($prefix)
    {
        self::$prefix = $prefix;
    }

    public static function __callStatic($name, $arguments)
    {
        return (new static)->$name(...$arguments);
    }

    public function __call($name, $arguments)
    {
        return (new static)->$name(...$arguments);
    }

    public static function getRoutes()
    {
        return Storage::getRoutes();
    }

    public static function getRoute($name)
    {
        return Storage::getRoute($name);
    }

    public static function getPrefix()
    {
        return Storage::getPrefix();
    }

    public static function setRoutes($routes, $method)
    {
        Storage::setRoutes($routes, $method);
    }

    public static function setPrefix($prefix)
    {
        Storage::setPrefix($prefix);
    }

    public static function addRoute($uri, $method, $callback)
    {
        Storage::addRoute($method, $uri, $callback);
    }

    public static function addPrefix($prefix)
    {
        Storage::addPrefix($prefix);
    }

    public function __invoke()
    {
        $callback = func_get_arg(0);
        $callback(new static);
    }

    public static function to($uri)
    {
    }
}
