<?php

use Riyu\Helpers\Errors\AppException;

if (!function_exists('view')) {
    function view($view = null, $data = [], $mergeData = [])
    {
        return new App\Config\View($view, $data, $mergeData);
    }
}

if (!function_exists('controller')) {
    function controller($class = null, $method = null, $data = null)
    {
        return new App\Config\Controller($class, $method, $data);
    }
}

if (!function_exists('redirect')) {
    function redirect($url = null)
    {
        return new App\Config\Redirect($url);
    }
}
