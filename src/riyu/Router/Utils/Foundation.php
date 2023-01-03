<?php
namespace Riyu\Router\Utils;

use Riyu\Http\Request;

class Foundation
{
    public static function between($string)
    {
        $tmp = [];
        preg_match_all('/{(.*?)}/', $string, $matches);
        foreach ($matches[1] as $value) {
            $tmp[] = $value;
        }
        return $tmp;
    }

    public static function getParams($uri)
    {
        $data = self::between($uri);
        $uri = preg_replace('/\//', '\/', $uri);
        $uri = preg_replace('/\{[a-zA-Z0-9 ]+\}/', '([a-zA-Z0-9 ]+)', $uri);
        $uri = '/^' . $uri . '$/';
        $url = Request::getRoute();
        $url = urldecode($url);
        preg_match($uri, $url, $matches);
        array_shift($matches);
        $params = [];
        foreach ($data as $key => $value) {
            $params[$value] = $matches[$key];
        }
        return $params;
    }
}
