<?php
namespace Riyu\Commands\Helpers;

use Riyu\Commands\Foundation;

class Create
{
    public function model($name)
    {
        if ($name) {
            (new Foundation)->model($name);
        } else {
            echo "\n";
            echo (new Color)->red("Please provide a model name");
            echo "\n";
            echo "\n";
        }
    }

    public function controller($name)
    {
        if ($name) {
            (new Foundation)->controller($name);
        } else {
            echo "\n";
            echo (new Color)->red("Please provide a controller name");
            echo "\n";
            echo "\n";
        }
    }

    public function route($method, $uri, $action = null, $name = null)
    {
        if ($method && $uri) {
            (new Foundation)->route($method, $uri, $action, $name);
        } else {
            echo "\n";
            echo (new Color)->red("Please provide a uri and action");
            echo "\n";
            echo "\n";
        }
    }

    public function all($name)
    {
        (new Foundation)->all($name);
    }
}