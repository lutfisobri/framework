<?php
namespace Riyu\Helpers\Errors;

use Riyu\Helpers\Storage\GlobalStorage;

class ViewError extends GlobalStorage
{
    protected static $path;

    public function __construct()
    {
        static::$path = self::get('path') . 'resources/views/errors/';
    }

    public static function setPath($path)
    {
        self::$path = $path;
    }

    public static function code($code)
    {
        $path = self::$path . $code . '.php';
        if (file_exists($path)) {
            require_once $path;
            http_response_code($code);
            exit;
        }
        require_once self::$path . '500.php';
        http_response_code(500);
        exit;
    }
}