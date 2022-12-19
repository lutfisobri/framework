<?php 
namespace Riyu\Router\Utils;

trait Group
{
    protected static $prefix = "";

    public static function group($prefix, $callback)
    {
        static::$prefix = $prefix;
        $callback(new static);
    }

    public static function __callStatic($name, $arguments)
    {
        return (new static)->$name(...$arguments);
    }

    public static function get($uri, $callback)
    {
        $uri = static::$prefix . $uri;
        Router::get($uri, $callback);
    }

    public static function post($uri, $callback)
    {
        $uri = static::$prefix . $uri;
        Router::post($uri, $callback);
    }

    public static function put($uri, $callback)
    {
        $uri = static::$prefix . $uri;
        Router::put($uri, $callback);
    }

    public static function delete($uri, $callback)
    {
        $uri = static::$prefix . $uri;
        Router::delete($uri, $callback);
    }

    public static function patch($uri, $callback)
    {
        $uri = static::$prefix . $uri;
        Router::patch($uri, $callback);
    }

    public function __invoke()
    {
        return $this;
    }

    public function __call($name, $arguments)
    {
        return $this;
    }
}