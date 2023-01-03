<?php

namespace Riyu\Router;

use Closure;
use ReflectionClass;
use ReflectionFunction;
use ReflectionMethod;
use Riyu\Http\Request;

class Resolver
{
    public static function aliases()
    {
        return [
            'request' => Request::class,
        ];
    }

    public static function resolveClass($class)
    {
        $aliases = self::aliases();

        if (!is_null($class) && is_string($class)) {
            if (array_key_exists($class, $aliases)) {
                return $aliases[$class];
            }
        }

        return $class;
    }

    public static function resolveMethod($class, $method)
    {
        $class = self::resolveClass($class);
        $class = new $class;
        return $class->$method();
    }

    public static function resolveData($class, $method, $data)
    {
        $name = array();

        $object = self::getArgument($class, $method);
        foreach ($object as $key => $value) {
            $name[] = $value->name;
        }

        if (in_array('request', $name)) {
            $key = array_search('request', $name);
            $object[$key] = new Request;
            $object[$key]->set($data);
        }

        if (in_array('Riyu\Http\Request', $name)) {
            $key = array_search('Riyu\Http\Request', $name);
            $object[$key] = new Request;
            $object[$key]->set($data);
        }

        $params = array();

        foreach ($object as $key => $value) {
            if (isset($value->name) && $value->name != null) {
                $params[] = $data[$value->name];
            } else {
                $params[] = $value;
            }
        }

        return $class->$method(...$params);
    }

    public static function resolveDataWithParams($class, $method, $data, $params)
    {
        $class = self::resolveClass($class);
        $class = new $class;
        return $class->$method($data, $params);
    }

    public static function resolveWithParams($class, $method, $params)
    {
        $args = self::resolveClass($params[0]->name);

        if (isset($args) && $args != null) {
            $args = new $args;
            return $class->$method($args);
        }
        
        return $class->$method(json_encode($params));
    }

    public static function resolve($callback, $data = null)
    {
        if ($callback instanceof Closure) {
            if ($data == null) {
                return $callback();
            }

            $name = array();
            $class = self::resolveClosure($callback);

            foreach ($class as $key => $value) {
                $name[] = $value;
            }

            if (in_array('request', $name)) {
                $key = array_search('request', $name);
                $class[$key] = new Request;
                $class[$key]->set($data);
            }

            if (in_array('Riyu\Http\Request', $name)) {
                $key = array_search('Riyu\Http\Request', $name);
                $class[$key] = new Request;
                $class[$key]->set($data);
            }

            $params = array();

            foreach ($class as $key => $value) {
                if ($value instanceof Request) {
                    $params[] = $value;
                } else {
                    $params[] = $data[$value];
                }
            }

            return $callback(...$params);
        } else {
            $class = new ReflectionClass($callback);
            $class = $class->name;
        }
    }

    public static function resolveClosure(Closure $closure)
    {
        $class = new ReflectionFunction($closure);
        $class = $class->getParameters();
        
        foreach ($class as $key => $value) {
            $class[$key] = $value->name;
        }

        return $class;
    }

    public static function getArgument($class, $method)
    {
        $tmp = new ReflectionMethod($class, $method);
        return $tmp->getParameters();
    }

    public static function __callStatic($name, $arguments)
    {
        if (method_exists(self::class, $name)) {
            return self::$name(...$arguments);
        }
    }

    public function __call($name, $arguments)
    {
        if (method_exists(self::class, $name)) {
            return self::$name(...$arguments);
        }
    }
}
