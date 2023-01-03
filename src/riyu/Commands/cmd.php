<?php

namespace Riyu\Commands;

use Riyu\Commands\Helpers\Color;
use Riyu\Commands\Helpers\listcmd;
use Riyu\Helpers\Storage\GlobalStorage;

class cmd
{
    private $help = [
        '--help', '-h', '-help', 'help', 'list', 'list:commands', 'list:cmd',
        '--h', '-H', '-Help', 'Help', 'List', 'List:commands', 'List:cmd',
        'l', 'L',
    ];

    private $create = [
        'create:model', 'create:controller', 'create:route', 'create:database',
    ];

    private $route = [
        'route:list', 'list:routes', 'routes:list', 'list:route', 'route:list',
    ];

    private $db = [
        'db:up', 'db:down', 'db:create'
    ];

    public function __construct($command)
    {
        if (isset($command[1])) {
            switch ($command[1]) {
                case in_array($command[1], $this->help):
                    $this->help($command);
                    break;
                case in_array($command[1], $this->create):
                    $this->run($command);
                    break;
                case in_array($command[1], $this->route):
                    $this->routes();
                    break;
                case in_array($command[1], $this->db):
                    $this->database($command);
                    break;
                case 'serve':
                    $this->serve();
                    break;
                default:
                    $this->run($command);
                    break;
            }
        } else {
            $this->help($command);
        }
    }

    public static function run($commands)
    {
        $raw = explode(':', $commands[1]);
        $class = $raw[0];
        $method = $raw[1] ?? null;
        $params1 = $commands[2] ?? null;
        $params2 = $commands[3] ?? null;
        $params3 = $commands[4] ?? null;
        $params = array($params1, $params2, $params3);
        $class = ucfirst($class);

        if ($class == 'create' || $class == 'Create') {
            if ($params2 == '--all') {
                $class = 'Riyu\Commands\Helpers\Create';
                $class = new $class;
                return $class->all($params1);
            }
        }

        if ($commands == 'help' || $class == 'Help') {
            $class = 'Riyu\Commands\Helpers\Help';
            return $class = new $class(...$params);
        }

        if (file_exists(__DIR__ . '/Helpers/' . $class . '.php')) {
            $class = 'Riyu\Commands\Helpers\\' . $class;
            $class = new $class;
            if (method_exists($class, $method)) {
                return $class->$method(...$params);
            } else {
                echo "\n";
                echo (new Color)->red("Command not found");
                echo "\n";
                echo "\n";
            }
        } else {
            echo "\n";
            echo (new Color)->red("Command not found");
            echo "\n";
            echo "\n";
        }
    }

    public function routes()
    {
        echo "\n";
        $routes = GlobalStorage::get('routes');
        $get = $routes['GET'];
        $post = $routes['POST'];
        $put = $routes['PUT'];
        $delete = $routes['DELETE'];
        $line = (new Color)->blue('---------------------------------');
        echo $line . " ", (new Color)->yellow('Routes') . " " . $line;
        echo "\n";
        foreach ($get as $key => $value) {
            echo (new Color)->green("GET") . " " . (new Color)->white($value);
            echo "\n";
        }
    }

    public function database($command)
    {
        $class = 'Riyu\Commands\Helpers\Database';
        $class = new $class;
        $cmd = explode(':', $command[1]);
        $method = $cmd[1];
        $params = $command[2] ?? null;
        if (method_exists($class, $method)) {
            return $class->$method($params);
        } else {
            echo "\n";
            echo (new Color)->red("Command not found");
            echo "\n";
            echo "\n";
        }
    }

    public function help($commands)
    {
        $params = $commands[2] ?? '';
        $class = 'Riyu\Commands\Helpers\Help';
        return $class = new $class($params);
    }

    public function serve()
    {
        $class = 'Riyu\Commands\Helpers\Serve';
        return $class = new $class;
    }
}
