<?php

use Riyu\App\Config;
use Riyu\Helpers\Errors\AppException;
use Riyu\Helpers\Errors\Message;

if (!function_exists('view')) {
    function view($view = null, $data = [], $mergeData = [])
    {
        try {
            return new App\Config\View($view, $data, $mergeData);
        } catch (\Throwable $th) {
            $config = Config::get('app');
            if ($config['debug']) {
                echo $th;
            } else {
                throw new AppException(Message::exception(500, json_encode($view)));
            }
        }
    }
}

if (!function_exists('controller')) {
    function controller($class = null, $method = null, $data = null)
    {
        try {
            return new App\Config\Controller($class, $method, $data);
        } catch (\Throwable $th) {
            $config = Config::get('app');
            if ($config['debug']) {
                echo $th;
            } else {
                throw new AppException(Message::exception(500, json_encode($class)));
            }
        }
    }
}

if (!function_exists('redirect')) {
    function redirect($url = null)
    {
        try {
            return new App\Config\Redirect($url);
        } catch (\Throwable $th) {
            $config = Config::get('app');
            if ($config['debug']) {
                echo $th;
            } else {
                throw new AppException(Message::exception(100, json_encode($url)));
            }
        }
    }
}
