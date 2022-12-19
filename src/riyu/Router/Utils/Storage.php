<?php

namespace Riyu\Router\Utils;

use Riyu\Helpers\Storage\GlobalStorage;

class Storage
{
    private static $routes = [];

    private static $routesGroup = [];

    private static $methods = [
        "GET" => [],
        "POST" => [],
        "PUT" => [],
        "DELETE" => [],
        "PATCH" => []
    ];

    private static $prefix = "";

    public static function getRoutes()
    {
        return self::$routes;
    }

    public static function addGroup($prefix, $routes, $method)
    {
        self::$routesGroup[$prefix][$method] = $routes;
    }

    public static function getRoute($name)
    {
        if (in_array($name, self::$routes['GET'])) {
            return self::$routes['GET'][$name];
        } else {
            echo "Route not found";
        }
    }

    public static function getRoutesGroup()
    {
        return self::$routesGroup;
    }

    public static function getPrefix()
    {
        return self::$prefix;
    }

    public static function setRoutes($routes, $method)
    {
        self::$routes[$method] = $routes;
    }

    public static function setPrefix($prefix)
    {
        self::$prefix = $prefix;
    }

    public static function addRoute($method, $uri, $callback)
    {
        if (array_key_exists($method, self::$routes)) {
            self::$routes[$method][] = $uri;
            self::$methods[$method][] = $callback;
        } else {
            self::$routes[$method] = [$uri];
            self::$methods[$method] = [$callback];
        }
        GlobalStorage::set("routes", self::$routes);
    }

    public static function addPrefix($prefix)
    {
        self::$prefix .= $prefix;
    }

    public static function clearRoutes()
    {
        self::$routes = [
            "GET" => [],
            "POST" => [],
            "PUT" => [],
            "DELETE" => [],
            "PATCH" => []
        ];
    }

    public static function getMethod($method)
    {
        return self::$methods[$method];
    }

    public static function getMethods()
    {
        return self::$methods;
    }

    public static function clearPrefix()
    {
        self::$prefix = "";
    }

    public static function clearAll()
    {
        self::clearRoutes();
        self::clearPrefix();
    }

    public static function clear($type)
    {
        if ($type == "routes") {
            self::clearRoutes();
        } elseif ($type == "prefix") {
            self::clearPrefix();
        } elseif ($type == "all") {
            self::clearAll();
        }
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
}