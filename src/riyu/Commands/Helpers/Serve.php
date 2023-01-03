<?php

namespace Riyu\Commands\Helpers;

use Riyu\App\Config;

class Serve
{
    public function __construct()
    {
        $this->run();
    }

    public function run()
    {
        $this->view();
        $this->exec();
    }

    public function exec()
    {
        $command = 'php -S localhost:8000 -f index.php';
        shell_exec($command);
    }

    public function view()
    {
        echo PHP_EOL;
        echo Color::green("Riyu Framework");
        echo PHP_EOL;
        echo PHP_EOL;
        echo Color::green("Server started on");
        echo Color::white(" http://localhost:8000");
        echo PHP_EOL;
        echo PHP_EOL;
        echo Color::red("Press Ctrl+C to quit.");
        echo PHP_EOL;
        echo PHP_EOL;
    }
}
