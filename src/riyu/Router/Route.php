<?php
namespace Riyu\Router;

use Riyu\Router\Utils\Router;

/**
 * @method static \Riyu\Router\Utils\Router get(string $uri, callable $callback)
 * @method static \Riyu\Router\Utils\Router put(string $uri, callable $callback)
 * @method static \Riyu\Router\Utils\Router delete(string $uri, callable $callback)
 * @method static \Riyu\Router\Utils\Router patch(string $uri, callable $callback)
 * @method static \Riyu\Router\Utils\Router group(string $prefix, callable $callback)
 * @method static \Riyu\Router\Utils\Router prefix(string $prefix)
 * @method static \Riyu\Router\Utils\Router getRoutes()
 * @method static \Riyu\Router\Utils\Router getRoute(string $name)
 * @method static \Riyu\Router\Utils\Router getPrefix()
 * @method static \Riyu\Router\Utils\Router setRoutes(array $routes, string $method)
 * @method static \Riyu\Router\Utils\Router setPrefix(string $prefix)
 * @method static \Riyu\Router\Utils\Router addRoute(string $uri, string $method, callable $callback)
 * @method static \Riyu\Router\Utils\Router addPrefix(string $prefix)
 * 
 * @package Riyu\Router
 */
class Route extends Router
{
}
