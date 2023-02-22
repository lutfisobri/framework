<?php
namespace Riyu\Http;

abstract class Foundation
{
    use Input;

    public static $storage = [];

    protected $request;

    public function __construct()
    {
        $this->hiddenHeader();
        // $this->setcookieRiyu();
        $this->booting();
    }

    public function booting()
    {
        $this->getAll();
    }

    public function setcookieRiyu()
    {
        if ($this->isRiyu()) {
            $this->generateCookie();
        }
         else {
            $cookie = $_COOKIE['Riyu'];
            header('Set-Cookie: Riyu=' . $cookie);
        }
    }

    public function isRiyu()
    {
        if (isset($_COOKIE['Riyu']) && !empty($_COOKIE['Riyu'])) {
            return false;
        }

        return true;
    }

    public function hiddenHeader()
    {
        header_remove('Server');
        header_remove('server');

        header_remove('X-Powered-By');
        header_remove('x-powered-by');

        header_remove('Expires');
        header_remove('expires');
        
        header_remove('Cache-Control');
        header_remove('cache-control');

        header('Cache-Control: no-cache, private');
        header('X-Powered-By: Riyu Framework');
    }

    public function generateCookie()
    {
        $expires = date('Y-m-d H:i:s', strtotime('+7 dayss'));
        $cookie = bin2hex(random_bytes(256));
        header('Set-Cookie: Riyu=' . $cookie);
    }

    public function getGet()
    {
        foreach ($_GET as $key => $value) {
            $this->$key = $value;
        }
    }

    public function getPost()
    {
        foreach ($_POST as $key => $value) {
            $this->$key = $value;
        }
    }

    public function getRequest()
    {
        foreach ($_REQUEST as $key => $value) {
            $this->$key = $value;
        }
    }

    public function getServer()
    {
        foreach ($_SERVER as $key => $value) {
            $this->$key = $value;
        }
    }

    public function getCookie()
    {
        foreach ($_COOKIE as $key => $value) {
            $this->$key = $value;
        }
    }

    public function getSession()
    {
        foreach ($_SESSION as $key => $value) {
            $this->$key = $value;
        }
    }

    public function getFile()
    {
        foreach ($_FILES as $key => $value) {
            $this->$key = $value;
        }
    }

    public function getJson()
    {
        $json = file_get_contents('php://input');
        if ($json) {
            $this->set(json_decode($json, true));
        }
    }

    public function getPut()
    {
        if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == 'PUT') {
            parse_str(file_get_contents('php://input'), $put);
            $this->set($put);
        }
    }

    public function getMultipart()
    {
        if (isset($_SERVER['CONTENT_TYPE']) && strpos($_SERVER['CONTENT_TYPE'], 'multipart/form-data') !== false) {
            $this->set($_POST);
        }
    }

    public function getForm()
    {
        if (isset($_SERVER['CONTENT_TYPE']) && strpos($_SERVER['CONTENT_TYPE'], 'application/x-www-form-urlencoded') !== false) {
            $this->set($_POST);
        }
    }

    public function getAll()
    {
        $this->getGet();
        $this->getPost();
        $this->getRequest();
        $this->getServer();
        $this->getCookie();
        $this->getSession();
        $this->getFile();
        $this->getJson();
        $this->getPut();
        $this->getMultipart();
        $this->getForm();
    }

    public function set($data)
    {
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                $this->$key = $value;
            }
        } else {
            $this->input($data);
        }
    }

    public function has($key)
    {
        return isset($this->$key);
    }

    private function input($data)
    {
        $this->request = $data;
    }

    public static function __callStatic($name, $arguments)
    {
        $instance = new static;
        return $instance->$name(...$arguments);
    }

    public function __call($name, $arguments)
    {
        return $this->$name(...$arguments);
    }
}