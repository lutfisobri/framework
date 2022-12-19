<?php
namespace Riyu\Views\Engine;

class Engines
{
    protected $data = [];

    protected $path;

    public function setPath($path)
    {
        $this->path = $path;
    }

    public function render($view, $data)
    {
        $this->data = $data;
        $file = $this->path . $view . '.php';
        if (file_exists($file)) {
            print_r(ob_get_level());
            extract($this->data);
            ob_start();
            require $file;
            $content = ob_get_clean();
            return $content;
        }
        throw new \Exception("Template file {$file} not found", 500);
    }

    public function test($view, $data)
    {
        $test = $this->render($view, $data);
        echo $this->php($test);
    }

    public function php($content)
    {
        $data = $this->data;
        ob_start();
        $content = preg_replace_callback('/{{\s*(.*?)\s*}}/', function ($matches) {
            return '<?php echo '.$matches[1].'; ?> s;ldkf';
        }, $content);
        ob_flush();
        return $content;
    }

    public function other($content)
    {
        $content = preg_replace_callback('/@php(.*?)@endphp/s', function ($matches) {
            return '<?php '.$matches[1].' ?>';
        }, $content);
        $content = preg_replace_callback('/@if(.*?)@endif/s', function ($matches) {
            return '<?php if'.$matches[1].': ?>';
        }, $content);
    }
}