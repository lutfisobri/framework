<?php

namespace Riyu\Views;

use Riyu\Helpers\Errors\AppException;

class Engine
{
    /**
     * Path for view
     *
     * @var string
     */
    protected $path;

    /**
     * View name
     *
     * @var string
     */
    protected $view;

    /**
     * Data for view
     *
     * @var array
     */
    protected $data = [];

    /**
     * Merge data for view
     *
     * @var array
     */
    protected $mergeData = [];

    /**
     * Sections for view
     *
     * @var array
     */
    protected $sections = [];

    /**
     * Stack for section
     *
     * @var array
     */
    protected $sectionStack = [];

    /**
     * Raw all sections
     *
     * @var array
     */
    protected $rawSections = [];

    /**
     * All files
     *
     * @var array
     */
    protected $allFiles = [];

    /**
     * Set path for view
     *
     * @param string $path
     * @return void
     */
    public function setPath($path)
    {
        $this->path = $path;
        foreach (glob($path . '*.php') as $file) {
            $this->allFiles[] = $file;
        }
        foreach (glob($path . '*', GLOB_ONLYDIR) as $dir) {
            foreach (glob($dir . '/*.php') as $file) {
                $this->allFiles[] = $file;
            }
        }
        // $this->setSectionAllFiles();
        return;
    }

    /**
     * Set all files
     *
     * @return void
     */
    public function setSectionAllFiles()
    {
        foreach ($this->allFiles as $file) {
            $this->rawSections[basename($file, '.php')] = file_get_contents($file);
        }
    }

    /**
     * Start render view
     *
     * @param string $view
     * @param array $data
     * @param array $mergeData
     * @return void
     */
    public function render($view, $data = [], $mergeData = [])
    {
        $this->view = $view;
        $this->data = $data;
        $this->mergeData = $mergeData;
        echo $this->renderView();
    }

    /**
     * Render view
     *
     * @return void
     */
    public function renderView()
    {
        $path = $this->path . $this->view . '.php';
        if (file_exists($path)) {
            try {
                $content = file_get_contents($path);
                return $this->inject($content);
            } catch (\Throwable $th) {
                echo $th;
            }
        } else {
            http_response_code(404);
        }
    }

    /**
     * Inject script to view
     *
     * @param string $content
     * @return void
     */
    public function injectScript($content)
    {
        $content = preg_replace_callback('/@script\((.+?)\)/', function ($matches) use ($content) {
            $script = $matches[1];
            if (isset($this->rawSections[$script])) {
                return $this->rawSections[$script];
            }
            return '';
        }, $content);
        return $content;
    }

    /**
     * Inject php to view
     *
     * @param string $content
     * @return void
     */
    public function injectPHP($content)
    {
        $content = preg_replace_callback('/@php(.+?)@endphp/s', function ($matches) use ($content) {
            return $matches[1];
        }, $content);
        return $content;
    }

    /**
     * Inject yields to view
     *
     * @param string $content
     * @return void
     */
    public function injectYields($content)
    {
        $content = preg_replace_callback('/@yield\((.+?)\)/', function ($matches) use ($content) {
            $section = $matches[1];
            if (isset($this->sections[$section])) {
                return $this->sections[$section];
            }
            return '';
        }, $content);
        return $content;
    }

    /**
     * Inject section to view
     *
     * @param string $content
     * @return void
     */
    public function injectSections($content)
    {
        $sections = $this->sections;
        $content = preg_replace_callback('/@section\((.+?)\)/', function ($matches) use ($sections) {
            $section = $matches[1];
            if (isset($sections[$section])) {
                return $sections[$section];
            }
            return '';
        }, $content);
        $content = preg_replace_callback('/@endsection/', function ($matches) {
            $section = array_pop($this->sectionStack);
            if (isset($this->sections[$section])) {
                return $this->sections[$section];
            }
            return '';
        }, $content);
        return $content;
    }

    /**
     * Inject data to view
     *
     * @param string $content
     * @return void
     */
    public function injectData($content)
    {
        $data = $this->data;
        $data = array_merge($data, $this->mergeData);
        $data = json_decode(json_encode($data));
        $content = preg_replace_callback('/{{\s*(.+?)\s*}}/', function ($matches) use ($data) {
            $key = $matches[1];
            if (isset($data->$key)) {
                return $data->$key;
            }
            if (preg_match('/\[\s*(.+?)\s*\]->\s*(.*)\s*/', $key, $matches)) {
                $key = $matches[1];
                $key2 = $matches[2];
                if (strpos($key, "'") !== false) {
                    $key = str_replace("'", "", $key);
                };
                if (is_array($data[$key]) || is_object($data[$key])) {
                    if (is_array($data[$key]->$key2) || is_object($data[$key]->$key2)) {
                        return '';
                    }
                    if ($data[$key]->$key2 != '' && $data[$key]->$key2 != null) {
                        return $data[$key]->$key2;
                    }
                }
                return '';
            }
            
            if (preg_match('/\[\s*(.+?)\s*\]->\s*(.*)\s*/', $key, $matches)) {
                $key = $matches[1];
                $key2 = $matches[2];
                if (strpos($key, "'") !== false) {
                    $key = str_replace("'", "", $key);
                };
                if ($data[$key]->$key2 != '' && $data[$key]->$key2 != null) {
                    return $data[$key]->$key2;
                }
                return '';
            }
            return '';
        }, $content);
        return $content;
    }

    /**
     * Inject function to view
     *
     * @param string $content
     * @return void
     */
    public function injectFunctions($content)
    {
        $content = preg_replace_callback('/@yield\((.+?)\)/', function ($matches) {
            $section = $matches[1];
            if (isset($this->sections[$section])) {
                return $this->sections[$section];
            }
            return '';
        }, $content);
        return $content;
    }

    /**
     * Inject include to view
     *
     * @param string $content
     * @return void
     */
    public function injectIncludes($content)
    {
        $content = preg_replace_callback('/@include\((.+?)\)/', function ($matches) {
            $view = $matches[1];
            $path = $this->path . $view . '.php';
            $path = str_replace("'", '', $path);
            $path = str_replace('"', '', $path);
            if (file_exists($path)) {
                try {
                    $content = file_get_contents($path);
                    return $this->inject($content);
                } catch (\Throwable $th) {
                    echo $th;
                }
            } else {
                http_response_code(404);
            }
        }, $content);
        return $content;
    }

    /**
     * Inject extends to view
     *
     * @param string $content
     * @return void
     */
    public function injectExtends($content)
    {
        $content = preg_replace_callback('/@extends\((.+?)\)/', function ($matches) {
            $view = $matches[1];
            $path = $this->path . $view . '.php';
            $path = str_replace("'", '', $path);
            $path = str_replace('"', '', $path);
            if (file_exists($path)) {
                try {
                    $content = file_get_contents($path);
                    return $this->inject($content);
                } catch (\Throwable $th) {
                    echo $th;
                }
            } else {
                http_response_code(404);
            }
        }, $content);
        return $content;
    }

    /**
     * Inject all to view
     *
     * @param string $content
     * @return void
     */
    public function inject($content)
    {
        $content = $this->injectData($content);
        $content = $this->injectYields($content);
        $content = $this->injectIncludes($content);
        $content = $this->injectFunctions($content);
        $content = $this->injectExtends($content);
        $content = $this->injectPHP($content);
        $content = $this->injectSections($content);
        return $content;
    }

    /**
     * Start section
     * 
     * @param string $section
     * @return void
     */
    public function start($section)
    {
        if (ob_start()) {
            $this->sectionStack[] = $section;
        }
    }

    /**
     * Stop section
     * 
     * @return void
     */
    public function stop()
    {
        return array_pop($this->sectionStack);
    }

    public function section($section)
    {
        if (isset($this->sections[$section])) {
            return $this->sections[$section];
        }
        if (ob_start()) {
            $this->sectionStack[] = $section;
        }
    }

    public function endSection()
    {
        $section = array_pop($this->sectionStack);
        $this->sections[$section] = ob_get_clean();
    }

    public function yield($section)
    {
        if (isset($this->sections[$section])) {
            return $this->sections[$section];
        }
    }

    public function extends($view)
    {
        $this->start($view);
    }

    public function include($view)
    {
        $this->sections[$view] = $this->render($view);
    }

    public function replace($name, $content)
    {
        $this->sections[$name] = $content;
        return $this;
    }

    public function sectionExists($name)
    {
        return isset($this->sections[$name]);
    }

    public function getSections()
    {
        return $this->sections;
    }

    public function getData()
    {
        return $this->data;
    }

    public function __call($name, $arguments)
    {
        if (isset($this->data[$name])) {
            return call_user_func_array($this->data[$name], $arguments);
        }

        if (isset($this->sections[$name])) {
            return call_user_func_array($this->sections[$name], $arguments);
        }

        if (method_exists($this, $name)) {
            return call_user_func_array([$this, $name], $arguments);
        }

        throw new AppException("Method {$name} does not exist.");
    }

    public static function __callStatic($name, $arguments)
    {
        $instance = new static;
        return call_user_func_array([$instance, $name], $arguments);
    }
}
