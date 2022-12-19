<?php

namespace Riyu\Router;

use Closure;
use ReflectionClass;
use ReflectionFunction;
use ReflectionMethod;
use Riyu\App\Config;
use Riyu\Helpers\Errors\AppException;
use Riyu\Helpers\Errors\Message;
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
        try {
            $class = self::resolveClass($class);
            $class = new $class;
            return $class->$method();
        } catch (\Throwable $th) {
            $config = Config::get('app');
            if ($config['debug']) {
                echo $th;
            } else {
                throw new AppException(Message::exception(500, json_encode($class)));
            }
        }
    }

    public static function resolveData($class, $method, $data)
    {
        try {
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
        } catch (\Throwable $th) {
            $config = Config::get('app');
            if ($config['debug']) {
                echo $th;
            } else {
                throw new AppException(Message::exception(500, json_encode($class)));
            }
        }
    }

    public static function resolveDataWithParams($class, $method, $data, $params)
    {
        try {
            $class = self::resolveClass($class);
            $class = new $class;
            return $class->$method($data, $params);
        } catch (\Throwable $th) {
            $config = Config::get('app');
            if ($config['debug']) {
                echo $th;
            } else {
                throw new AppException(Message::exception(500, json_encode($class)));
            }
        }
    }

    public static function resolveWithParams($class, $method, $params)
    {
        try {
            $args = self::resolveClass($params[0]->name);
            if (isset($args) && $args != null) {
                $args = new $args;
                return $class->$method($args);
            } else {
                return $class->$method(json_encode($params));
            }
        } catch (\Throwable $th) {
            $config = Config::get('app');
            if ($config['debug']) {
                echo $th;
            } else {
                throw new AppException(Message::exception(500, json_encode($class)));
            }
        }
    }

    public static function resolve($callback, $data = null)
    {
        try {
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
        } catch (\Throwable $th) {
            $config = Config::get('app');
            if ($config['debug']) {
                echo $th;
            } else {
                throw new AppException(Message::exception(500, json_encode($callback)));
            }
        }
    }

    public static function resolveClosure(Closure $closure)
    {
        try {
            $class = new ReflectionFunction($closure);
            $class = $class->getParameters();
            foreach ($class as $key => $value) {
                $class[$key] = $value->name;
            }
            return $class;
        } catch (\Throwable $th) {
            $config = Config::get('app');
            if ($config['debug']) {
                echo $th;
            } else {
                throw new AppException(Message::exception(500, json_encode($closure)));
            }
        }
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
