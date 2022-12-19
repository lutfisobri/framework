<?php

namespace Riyu\Commands\Helpers;

class Help
{
    public $model = [
        'model', 'create:model',
        'Usage: php riyu create:model ModelName',
        'create a model', 'create model', 'usage model',
        'mdl',
    ];

    public $controller = [
        'controller', 'create:controller',
        'Usage: php riyu create:controller ControllerName',
        'create a controller', 'create controller', 'usage controller'
    ];

    public $route = [
        'route', 'create:route',
        'Usage: php riyu create:route get|post|put|delete uri action name',
        'create a route', 'create route', 'usage route',
    ];

    public function __construct($name = null)
    {
        print_r($name);
        if ($name) {
            $this->help($name);
        } else {
            $this->list();
        }
    }

    public function list()
    {
        echo "\n";
        echo (new Color)->green("List of commands:");
        echo "\n";
        echo "\n";
        echo "  - " . (new Color)->green("create:model") . "         " . (new Color)->white("Create a model");
        echo "\n";
        echo "  - " . (new Color)->green("create:controller") . "    " . (new Color)->white("Create a controller");
        echo "\n";
        echo "  - " . (new Color)->green("create:route") . "         " . (new Color)->white("Create a route");
        echo "\n";
        echo "\n";
        echo "  - " . (new Color)->green("db:create") . "            " . (new Color)->white("Create a database");
        echo "\n";
        echo "  - " . (new Color)->green("db:up") . "                " . (new Color)->white("Upload all schema to database");
        echo "\n";
        echo "  - " . (new Color)->green("db:down") . "              " . (new Color)->white("Delete all schema from database");
        echo "\n";
        echo "\n";
    }

    public function help($name)
    {
        switch ($name) {
            case in_array($name, $this->model):
                echo "\n";
                echo $this->model();
                echo "\n";
                break;
            case in_array($name, $this->controller):
                echo "\n";
                echo $this->controller();
                echo "\n";
                break;
            case in_array($name, $this->route):
                echo "\n";
                echo $this->route();
                echo "\n";
                break;
            default:
                echo "\n";
                echo (new Color)->red("Command not found");
                echo "\n";
                echo "\n";
                break;
        }
    }

    public function model()
    {
        return (new Color)->green("create:model") . " " . (new Color)->white("Create a model") . " 
        
" . (new Color)->green("Usage:") . " 
    php riyu " . (new Color)->green("create:model") . " " . (new Color)->white("ModelName") . "

" . (new Color)->green("Example:") . " 
    php riyu " . (new Color)->green("create:model") . " " . (new Color)->white("User") . "
";
    }

    public function controller()
    {
        return (new Color)->green("create:controller") . " " . (new Color)->white("Create a controller") . "

" . (new Color)->green("Usage:") . "  
    " . (new Color)->green("create:controller") . " " . (new Color)->white("ControllerName") . "

" . (new Color)->green("Example:") . " 
    " . (new Color)->green("create:controller") . " " . (new Color)->white("HomeController") . "
";
    }

    public function route()
    {
        return (new Color)->green("create:route") . " " . (new Color)->white("Create a route") . "

" . (new Color)->green("Usage:") . "  
    " . (new Color)->green("create:route") . " " . (new Color)->white("get|post|put|delete") . " " . (new Color)->white("uri") . " " . (new Color)->white("action") . " " . (new Color)->white("name") . "
" . (new Color)->green("Example:") . "  
    " . (new Color)->green("create:route") . " " . (new Color)->white("get") . " " . (new Color)->white("/home") . " " . (new Color)->white("[HomeController::class]") . " " . (new Color)->white("home") . "
" . (new Color)->green("Example:") . "  
    " . (new Color)->green("create:route") . " " . (new Color)->white("post") . " " . (new Color)->white("/login") . " " . (new Color)->white("callback") . "
";
    }
}
