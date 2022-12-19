<?php

namespace Riyu\Router;

use Riyu\Helpers\Callback\Callback;
use Riyu\Helpers\Errors\AppException;
use Riyu\Helpers\Errors\Message;
use Riyu\Helpers\Errors\ViewError;
use Riyu\Http\Request;
use Riyu\Router\Utils\Foundation;
use Riyu\Router\Utils\Storage;

class Matches extends Storage
{
    public function __construct()
    {
        // try {
            //code...
            $request = new Request;
        //     $this->testMatch($request->getRoute(), $request->method(), $request->type());
        // } catch (\Throwable $th) {
        //     echo $th;
        //     //throw $th;
        // }
        $this->match($request->getRoute(), $request->method());
    }

    public function testMatch($uri, $method, $type)
    {
        $routes = self::getRoutes();
        $url = $uri;
        $type = ($type == "web") ? "web" : "api";
        $uri = $this->pregReplace($uri);
        foreach ($routes[$type][$method] as $route) {
            if (preg_match($uri, $route)) {
                try {
                    return $this->testAction($route, $method, $type);
                } catch (\Throwable $th) {
                    throw new AppException(Message::exception(501, [$route, $method]));
                }
            }
            $rout = $this->pregReplaceRoute($route);
            if (preg_match($rout, $url)) {
                try {
                    return $this->testAction($route, $method, $type);
                } catch (\Throwable $th) {
                    throw new AppException(Message::exception(501, [$route, $method]));
                }
            }
        }
        return ViewError::code(404);
    }

    public function testAction($route, $method, $type)
    {
        $routes = self::getRoutes();
        $methods = self::getMethods();
        $params = Foundation::getParams($route);
        $callback = $methods[$type][$method];
        $callback = $callback[array_search($route, $routes[$type][$method])];
        try {
            Storage::clearAll();
            return Callback::call($callback, $params);
        } catch (\Throwable $th) {
            throw new AppException(Message::exception(500, [$callback, $params]));
        }
    }

    public function match($uri, $method)
    {
        $routes = self::getRoutes();
        $url = $uri;
        $uri = $this->pregReplace($uri);
        foreach ($routes[$method] as $route) {
            if (preg_match($uri, $route)) {
                try {
                    return $this->callback($route, $method);
                } catch (\Throwable $th) {
                    throw new AppException(Message::exception(501, [$route, $method]));
                }
            }
            $rout = $this->pregReplaceRoute($route);
            if (preg_match($rout, $url)) {
                try {
                    return $this->callback($route, $method);
                } catch (\Throwable $th) {
                    throw new AppException(Message::exception(501, [$route, $method]));
                }
            }
        }
        $this->matchGroup($url, $method);
    }

    public function matchGroup($uri, $method)
    {
        $routes = self::getRoutes();
        $prefix = self::getPrefix();
        $url = $uri;
        $uri = $prefix . $uri;
        $uri = $this->pregReplace($uri);
        foreach ($routes[$method] as $route) {
            if (preg_match($uri, $route)) {
                try {
                    return $this->callback($route, $method);
                } catch (\Throwable $th) {
                    throw new AppException(Message::exception(501, [$route, $method]));
                }
            }
            $rout = $this->pregReplaceRoute($route);
            if (preg_match($rout, $url)) {
                return $this->callback($route, $method);
            }
        }
        return ViewError::code(404);
    }

    public function callback($route, $method)
    {
        $routes = self::getRoutes();
        $methods = self::getMethods();
        $params = Foundation::getParams($route);
        $callback = $methods[$method];
        $callback = $callback[array_search($route, $routes[$method])];
        try {
            Storage::clearAll();
            return Callback::call($callback, $params);
        } catch (\Throwable $th) {
            throw new AppException(Message::exception(501, [$callback, $params]));
        }
    }

    public function pregReplace($uri)
    {
        $uri = preg_replace('/\//', '\/', $uri);
        $uri = preg_replace('/\{[a-zA-Z0-9]+\}/', '([a-zA-Z0-9]+)', $uri);
        $uri = '/^' . $uri . '$/';
        return $uri;
    }

    public function pregReplaceRoute($route)
    {
        $route = preg_replace('/\//', '\/', $route);
        $route = preg_replace('/\{[a-zA-Z0-9]+\}/', '([a-zA-Z0-9]+)', $route);
        $route = '/^' . $route . '$/';
        return $route;
    }
}
