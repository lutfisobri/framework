<?php
namespace Riyu\Views;

class View
{
    protected $data = [];
    protected $path;

    public function __construct($path = null)
    {
        if (!$path) {
            $path = dirname(__DIR__) . '/views/';
        }
        $this->path = $path;
    }

    public function assign($name, $value)
    {
        $this->data[$name] = $value;
    }

    public function render($template)
    {
        $file = $this->path . $template . '.php';
        if (file_exists($file)) {
            extract($this->data);
            ob_start();
            require $file;
            $content = ob_get_clean();
            return $content;
        }
        throw new \Exception("Template file {$file} not found", 500);
    }
}