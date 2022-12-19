<?php

namespace Riyu\Commands\Helpers;

class Database
{
    public function create($name)
    {
        if ($name) {
            $path = 'app/Database/Schema';
            $names = strtolower($name);
            $name = ucfirst($name);
            $file = $path . '/' . $name . '.php';
            $content = $this->content($name, $names);
            $this->createFile($file, $content, $name);
        } else {
            echo "\n";
            echo (new Color)->red("Please provide a database name");
            echo "\n";
            echo "\n";
        }
    }

    public function content($name, $names)
    {
        $content = "<?php
namespace App\Database\Schema;

use Riyu\Database\Schema\Blueprint;
use Riyu\Database\Schema\Schema;

class $name extends Schema
{
    public function up()
    {
        return \$this->create('$names', function (Blueprint $" . "table) {
            $" . "table->id();
            $" . "table->string('name')->unique();
            $" . "table->string('email')->unique();
            $" . "table->string('password');
            $" . "table->rememberToken();
        });
    }

    public function down()
    {
        return \$this->drop('$names');
    }

} ";
        return $content;
    }

    public function createFile($file, $content, $name)
    {
        if (!file_exists($file)) {
            $file = fopen($file, 'w');
            fwrite($file, $content);
            fclose($file);
            echo "\n";
            echo (new Color)->green( $name . " created successfully");
            echo "\n";
            echo "\n";
        } else {
            echo "\n";
            echo (new Color)->red($name . " already exists");
            echo "\n";
            echo "\n";
        }
    }
    
    public function up()
    {
        $schema = scandir('app/Database/Schema/');
        $schema = array_diff($schema, ['.', '..']);
        $schema = array_values($schema);
        echo "\n";
        foreach ($schema as $key => $value) {
            $value = str_replace('.php', '', $value);
            $value = 'App\\Database\\Schema\\' . $value;
            $value = new $value;
            echo (new Color)->green($value->up());
            echo "\n";
        }
        echo "\n";
    }

    public function down()
    {
        $schema = scandir('app/Database/Schema/');
        $schema = array_diff($schema, ['.', '..']);
        $schema = array_values($schema);
        echo "\n";
        foreach ($schema as $key => $value) {
            $value = str_replace('.php', '', $value);
            $value = 'App\\Database\\Schema\\' . $value;
            $value = new $value;
            echo (new Color)->red($value->down());
            echo "\n";
        }
        echo "\n";
    }
}