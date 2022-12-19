<?php

namespace Riyu\Helpers\Callback;

use ReflectionMethod;
use Riyu\Helpers\Errors\AppException;
use Riyu\Helpers\Errors\Message;
use Riyu\Router\Resolver;

class Callback
{
    public static function call($callback, $data = null)
    {
        try {
            if (is_array($callback)) {
                $class = new $callback[0];
                $method = $callback[1];
                if (isset($method) && $method != null) {
                    $tmp = new ReflectionMethod($class, $method);
                    $params = $tmp->getParameters();
                    if (count($params) > 0) {
                        if ($data != null) {
                            return Resolver::resolveData($class, $method, $data);
                        } else {
                            return Resolver::resolveWithParams($class, $method, $params);
                        }
                    } else {
                        if ($data != null) {
                            return Resolver::resolveData($class, $method, $data);
                        } else {
                            return Resolver::resolveMethod($class, $method);
                        }
                    }
                } else {
                    self::callable($callback, $data);
                }
            } else {
                self::object($callback, $data);
            }
        } catch (\Throwable $th) {
            throw new AppException(Message::exception(500, $callback));
        }
    }

    private static function object($callback, $data = null)
    {
        try {
            if (is_object($callback)) {
                return Resolver::resolve($callback, $data);
            } else {
                self::callable($callback, $data);
            }
        } catch (\Throwable $th) {
            throw new AppException(Message::exception(500, $callback));
        }
    }

    private static function callable($callback, $data = null)
    {
        if (is_callable($callback)) {
            if ($data != null) {
                try {
                    return $callback($data);
                } catch (\Throwable $th) {
                    throw new AppException(Message::exception(500, $callback));
                }
            } else {
                try {
                    return $callback();
                } catch (\Throwable $th) {
                    throw new AppException(Message::exception(500, $callback));
                }
            }
        } else {
            try {
                if (is_array($callback)) {
                    $class = new $callback[0];
                    $method = $callback[1];
                    if ($method == null) {
                        return $class->index();
                    } else {
                        return $class->$method();
                    }
                } else {
                    return $callback();
                }
            } catch (\Throwable $th) {
                echo $th;
                throw new AppException(Message::exception(500, $callback));
            }
        }
    }
}
