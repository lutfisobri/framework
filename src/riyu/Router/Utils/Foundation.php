<?php

namespace Riyu\Router\Utils;

use ReflectionFunction;
use Riyu\Helpers\Callback\Callback;
use Riyu\Helpers\Errors\AppException;
use Riyu\Http\Request;
use Riyu\Router\Utils;

class Foundation
{
    private static $routes = [
        "GET" => [],
        "POST" => [],
        "PUT" => [],
        "DELETE" => [],
        "PATCH" => []
    ];

    private static $prefix = "";

    private static $storage;

    public static function between($string)
    {
        $tmp = [];
        preg_match_all('/{(.*?)}/', $string, $matches);
        foreach ($matches[1] as $a) {
            $tmp[] = $a;
        }
        return $tmp;
    }

    public static function getParams($uri)
    {
        $data = self::between($uri);
        $uri = preg_replace('/\//', '\/', $uri);
        $uri = preg_replace('/\{[a-zA-Z0-9]+\}/', '([a-zA-Z0-9]+)', $uri);
        $uri = '/^' . $uri . '$/';
        $url = Request::getRoute();
        preg_match($uri, $url, $matches);
        array_shift($matches);
        $params = [];
        foreach ($data as $key => $value) {
            $params[$value] = $matches[$key];
        }
        return $params;
    }

    public static function getUrl()
    {
        $url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $url = explode('/', $url);
        $url = array_filter($url);
        $url = array_values($url);
        $url = array_slice($url, 1);
        $url = implode('/', $url);
        return "/" . $url;
    }

    public static function getMethod($uri, $method)
    {
        $mtd = Request::method();
        $gurl = self::getUrl();
        $not = array();
        $tmp = array();
        $yes = array();
        for ($i = 0; $i < count(self::$routes[$method]); $i++) {
            $tmp[] = self::$routes[$method][$i];
        }
        foreach ($tmp as $key => $value) {
            $data = strpos($value, '{');
            if ($data !== false) {
                $not[] = $value;
            } else {
                $yes[] = $value;
            }
        }
        if (in_array($uri, $yes)) {
            if ($mtd == $method) {
                return true;
            } else {
                http_response_code(405);
                exit;
            }
        } else {
            $cv = explode('/', $uri);
            $cv = array_filter($cv);
            $cv = array_values($cv);
            $url = explode('/', $gurl);
            $url = array_filter($url);
            $url = array_values($url);
            if (count($cv) == count($url)) {
                for ($i = 0; $i < count($cv); $i++) {
                    if ($cv[$i] == $url[$i]) {
                        if ($mtd == $method) {
                            return true;
                        }
                    } else {
                        $data = strpos($cv[$i], '{');
                        if ($data !== false) {
                            if ($mtd == $method) {
                                return true;
                            }
                        }
                    }
                }
            } else {
                // http_response_code(404);
                // echo self::$storage->validate($gurl, $method);
                // Storage::validate($gurl, $method);
            }
        }
    }

    // public static function getParams($uri)
    // {
    //     $cv = explode('/', $uri);
    //     $cv = array_filter($cv);
    //     $cv = array_values($cv);
    //     $url = explode('/', self::getUrl());
    //     $url = array_filter($url);
    //     $url = array_values($url);
    //     if (count($cv) == count($url)) {
    //         if ($cv[0] == $url[0]) {
    //             $data = [];
    //             $keys = self::between($uri);
    //             if ($keys == null) {
    //                 http_response_code(500);
    //                 throw new AppException('Invalid route parameters', 500);
    //                 exit;
    //             }
    //             for ($i = 0; $i < count($cv); $i++) {
    //                 if (strpos($cv[$i], '{') !== false) {
    //                     $data[] = $url[$i];
    //                 }
    //             }
    //             return array_combine($keys, $data);
    //         }
    //     }
    // }

    // private static function between($string)
    // {
    //     $string = ' ' . $string;
    //     $ini = strpos($string, "{");
    //     if ($ini == 0) return '';
    //     $ini += strlen("{");
    //     $tmp = substr($string, $ini);
    //     $tmp = str_replace("{", '', $tmp);
    //     $tmp = str_replace("}", ',', $tmp);
    //     $tmp = str_replace("/", '', $tmp);
    //     $tmp = explode(',', $tmp);
    //     $tmp = array_filter($tmp);
    //     $tmp = array_values($tmp);
    //     return $tmp;
    // }

    public static function callback($callback, $data = null)
    {
        return Callback::call($callback, $data);
    }

    // public static function build($uri, $callback, $method)
    // {
    //     self::addRoute($uri, $method);
    //     self::$storage = new Storage();
    //     self::$storage->addRoute($uri, $method, $callback);
    //     $data = strpos($uri, '{');
    //     if ($data === false) {
    //         if (self::getUrl() === $uri) {
    //             $mtd = Request::method();
    //             if ($mtd == $method) {
    //                 return self::callback($callback);
    //             }
    //         }
    //     } else {
    //         // $data = self::getParams($uri);
    //         if (self::getMethod($uri, $method) == true) {
    //             if ($data != null) {
    //                 return self::callback($callback, $data);
    //             }
    //         }
    //     }
    // }

    public static function group($callback)
    {
        $reflection = new ReflectionFunction($callback);
        print_r($reflection);
    }

    public static function prefix($prefix)
    {
        if (substr($prefix, 0, 1) == '/') {
            $prefix = substr($prefix, 1);
        } else {
            $prefix = $prefix;
        }
    }

    // public static function addRoute($route, $method = null)
    // {
    //     if (is_array($method)) {
    //         foreach ($method as $mtd) {
    //             self::$routes[$mtd][] = $route;
    //         }
    //     } else {
    //         self::$routes[$method][] = $route;
    //     }
    // }

    function array_combine($keys, $values)
    {
        $result = array();

        foreach ($keys as $i => $k) {
            $result[$k][] = $values[$i];
        }

        array_walk($result, function (&$v) {
            $v = (count($v) == 1) ? array_pop($v) : $v;
        });

        return $result;
    }

    function array_flatten($array)
    {
        if (!is_array($array)) {
            return FALSE;
        }
        $result = array();
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $result = array_merge($result, $this->array_flatten($value));
            } else {
                $result[$key] = $value;
            }
        }
        return $result;
    }

    // public static function validation($uri)
    // {
    //     if (!in_array($uri, self::$routes)) {
    //         http_response_code(404);
    //         exit;
    //     }
    // }

    public static function match(Storage $storage)
    {
        $routes = $storage->getRoutes();
        $uri = self::getUrl();
        $method = Request::method();
        foreach ($routes as $route => $methods) {
            if ($route == $uri) {
                if ($methods == $method) {
                    return true;
                }
            }
        }
    }
}
