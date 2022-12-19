<?php

namespace Riyu\Commands;

use Riyu\Commands\Helpers\Color;

class Foundation
{
    public function model($name)
    {
        echo "\n";
        $name = ucfirst($name);
        $path = 'app/Models/' . $name . '.php';
        $names = strtolower($name);
        if (!file_exists($path)) {
            $file = fopen($path, 'w');
            $content = $this->contentM($name, $names);
            fwrite($file, $content);
            fclose($file);
            echo (new Color)->green("Model $name created successfully");
        } else {
            echo (new Color)->red("Model $name already exists");
        }
        echo "\n";
        echo "\n";
    }

    public function controller($name)
    {
        echo "\n";
        $names = ucfirst($name);
        $path = 'app/Controllers/' . $names . 'Controller.php';
        $name = $names . 'Controller';
        $names = strtolower($names);
        if (!file_exists($path)) {
            $file = fopen($path, 'w');
            $content = $this->contentC($name, $names);
            fwrite($file, $content);
            fclose($file);
            echo (new Color)->green("Controller $name created successfully");
        } else {
            echo (new Color)->red("Controller $name already exists");
        }
        echo "\n";
        echo "\n";
    }

    public function route($method, $uri, $action = null, $name = null)
    {
        echo "\n";
        $file = fopen('routes/web.php', 'a');
        $content = $this->contentr($method, $uri, $action, $name);
        fwrite($file, $content);
        fclose($file);
        if ($name) {
            echo (new Color)->green("Route $name created successfully");
        } else {
            echo (new Color)->green("Route created successfully");
        }
        echo "\n";
        echo "\n";
    }

    public function migrate()
    {
        $schema = scandir(__DIR__ . '/../../app/Database/Schema/');
        $schema = array_diff($schema, ['.', '..']);
        $schema = array_values($schema);
        echo "\n";
        foreach ($schema as $key => $value) {
            $value = str_replace('.php', '', $value);
            $value = 'App\\Database\\Schema\\' . $value;
            $value = new $value;
            echo (new Color)->green($value->up());
        }
        echo "\n";
        echo "\n";
    }

    public function drop()
    {
        $schema = scandir(__DIR__ . '/../../app/Database/Schema/');
        $schema = array_diff($schema, ['.', '..']);
        $schema = array_values($schema);
        echo "\n";
        foreach ($schema as $key => $value) {
            $value = str_replace('.php', '', $value);
            $value = 'App\\Database\\Schema\\' . $value;
            $value = new $value;
            echo (new Color)->red($value->down());
        }
        echo "\n";
        echo "\n";
    }

    public function all($name)
    {
        echo "\n";
        $names = ucfirst($name);
        $path = 'app/Controllers/' . $names . 'Controller.php';
        $nameC = $names . 'Controller';
        $names = strtolower($names);
        if (!file_exists($path)) {
            $file = fopen($path, 'w');
            $content = $this->contentCAll($nameC, $names);
            fwrite($file, $content);
            fclose($file);
            echo (new Color)->green("Controller $nameC created successfully");
        } else {
            echo (new Color)->red("Controller $nameC already exists");
        }
        echo "\n";
        $name = ucfirst($name);
        $path = 'app/Models/' . $name . '.php';
        $names = strtolower($name);
        if (!file_exists($path)) {
            $file = fopen($path, 'w');
            $content = $this->contentM($name, $names);
            fwrite($file, $content);
            fclose($file);
            echo (new Color)->green("Model $name created successfully");
        } else {
            echo (new Color)->red("Model $name already exists");
        }
        echo "\n";

        $file = fopen('routes/web.php', 'a');
        $content = "

Route::group('/". $names ."', function () {
    Route::get('/', [".$nameC."::class,'index']);
    Route::get('/create', [".$nameC ."::class, 'create']);
    Route::post('/', [".$nameC."::class, 'store']);
    Route::get('/{id}', [".$nameC."::class, 'show']);
    Route::get('/{id}/edit', [".$nameC."::class, 'edit']);
    Route::put('/{id}', [".$nameC."::class, 'update']);
    Route::delete('/{id}', [".$nameC."::class, 'destroy']);
});";
        // $content = $this->contentr("get", "/".$names, "[".$nameC."::class,'index']", null);
        fwrite($file, $content);
        fclose($file);
        if ($name) {
            echo (new Color)->green("Route $name created successfully");
        } else {
            echo (new Color)->green("Route created successfully");
        }
        echo "\n";
        echo "\n";
    }

    public function contentCAll($name, $names)
    {
        return "<?php
namespace App\Controllers;

use App\Controllers\Controller;
use Riyu\Http\Request;

class $name extends Controller
{
    public function index()
    {
        return view('$names');
    }

    public function create()
    {
        return view('$names/create');
    }

    public function store(Request $"."request)
    {
        //
    }

    public function show($"."id)
    {
        //
    }

    public function edit($"."id)
    {
        //
    }

    public function update(Request $"."request, $"."id)
    {
        //
    }

    public function destroy(Request $"."request, $"."id)
    {
        //
    }
}";
    }

    public function contentC($name, $names)
    {
        return "<?php
namespace App\Controllers;

use App\Controllers\Controller;
use Riyu\Http\Request;

class $name extends Controller
{
    public function index()
    {
        return view('$names');
    }

    public function create()
    {
        return view('$names/create');
    }

    public function store(Request $"."request)
    {
        //
    }

    public function show($"."id)
    {
        //
    }

    public function edit($"."id)
    {
        //
    }

    public function update(Request $"."request, $"."id)
    {
        //
    }

    public function destroy(Request $"."request, $"."id)
    {
        //
    }
}";
    }

    public function contentM($name, $names)
    {
        return "<?php
namespace App\Models;

use Riyu\Database\Utils\Model;

class $name extends Model
{
    protected \$table = '$names';
}";
    }

    protected $method = [
        'get' => 'get',
        'post' => 'post',
        'put' => 'put',
        'patch' => 'patch',
        'group' => 'group',
        'delete' => 'delete',
    ];

    public function contentR($method, $uri, $action, $name)
    {
        if ($name) {
            $name = "// $name";
        } else {
            $name = null;
        }

        if (array_key_exists($method, $this->method)) {
            if ($action) {
                if ($action == 'callback') {
                    return "Route::$method('$uri', function () {
    return view('$uri');
}); $name \n";
                }
                return "Route::$method('$uri', $action); $name \n";
            } else {
                return "Route::$method('$uri', function () {
    return view('$uri');
}); $name \n";
            }
            return "Route::$method('$uri', $action); $name \n";
        }

        if (strpos($method, '/') !== false) {
            return "Route::get('$method', $uri); $name \n";
        }
    }
}
