<?php
namespace Riyu\App;

class Config
{
    public static $config = [];

    public static function set($key, $value)
    {
        self::$config[$key] = $value;
    }

    public static function get($key)
    {
        return self::$config[$key];
    }

    public static function all()
    {
        return self::$config;
    }

    public static function has($key)
    {
        return isset(self::$config[$key]);
    }

    public static function remove($key)
    {
        unset(self::$config[$key]);
    }

    public static function clear()
    {
        self::$config = [];
    }

    public static function load($file)
    {
        $config = require $file;
        self::$config = array_merge(self::$config, $config);
    }

    public function __set($name, $value)
    {
        self::set($name, $value);
    }

    public function __get($name)
    {
        return self::get($name);
    }

    public function __isset($name)
    {
        return self::has($name);
    }

    public function __unset($name)
    {
        self::remove($name);
    }

    public function __invoke()
    {
        return self::all();
    }

    public function __toString()
    {
        return json_encode(self::all());
    }

    public function __debugInfo()
    {
        return self::all();
    }

    public function __call($name, $arguments)
    {
        return self::$name(...$arguments);
    }

    public static function __callStatic($name, $arguments)
    {
        return self::$name(...$arguments);
    }
}