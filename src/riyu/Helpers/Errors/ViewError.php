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
        self::notFound();
    }

    private function notFound()
    {
        $dir = __DIR__ . '/Handler/resources';
        $path = $dir . '/500.php';
        require_once $path;
        http_response_code(500);
        exit;
    }
}