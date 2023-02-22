<?php

namespace Riyu\Http;

class Response
{
    protected static $content;

    protected static $code;

    protected static $method;

    protected static $headers;

    protected static $cookies;

    protected static $protocol;

    public static function code($code = 200)
    {
        self::$code = $code;
        return (new static);
    }

    public static function setMethod($method = 'GET')
    {
        self::$method = $method;
        return (new static);
    }

    public static function redirect($url = '/')
    {
        header('Location: ' . $url);
        exit;
    }

    public static function setHeaderType($type = 'text/html')
    {
        self::setHeader('Content-Type: ' . $type);
        return (new static);
    }

    public static function setHeader($header = "Content-Type: text/html")
    {
        self::$headers[] = $header;
        return (new static);
    }

    public static function setCookie($cookie = "name=value")
    {
        self::setHeader('Set-Cookie: ' . $cookie);
    }

    public static function setCookies($cookie = [])
    {
        foreach ($cookie as $key => $value) {
            self::setCookie($key . '=' . $value);
        }

        return (new static);
    }

    public static function setProtocol($protocol = 'HTTP/1.1')
    {
        self::$protocol = $protocol;
        return (new static);
    }

    public static function content($content)
    {
        self::$content = $content;
        return (new static);
    }

    public static function json($content)
    {
        self::setHeaderType('application/json');
        self::$content = json_encode($content);
        return (new static);
    }

    public static function hasCode()
    {
        return self::$code !== null;
    }

    public static function hasMethod()
    {
        return self::$method !== null;
    }

    public static function hasHeaders()
    {
        return self::$headers !== null;
    }

    public static function hasCookies()
    {
        return self::$cookies !== null;
    }

    public static function hasCookie($name)
    {
        return isset($_COOKIE[$name]);
    }

    public static function hasContent()
    {
        return self::$content !== null;
    }

    public static function hasProtocol()
    {
        return self::$protocol !== null;
    }

    public static function send()
    {
        if (self::hasCode()) {
            http_response_code(self::$code);
        }

        if (self::hasMethod()) {
            $_SERVER['REQUEST_METHOD'] = self::$method;
        }

        if (self::hasHeaders()) {
            foreach (self::$headers as $header) {
                header($header);
            }
        }

        if (self::hasCookies()) {
            foreach (self::$cookies as $cookie) {
                setcookie($cookie);
            }
        }

        if (self::hasProtocol()) {
            header(self::$protocol);
        }

        if (self::hasContent()) {
            echo self::$content;
        }

        self::$code = null;
        self::$method = null;
        self::$headers = null;
        self::$cookies = null;
        self::$content = null;
    }

    public static function sendJson($content)
    {
        self::json($content)->send();
    }

    public static function sendContent($content)
    {
        self::content($content)->send();
    }

    public function __destruct()
    {
        if (self::hasContent() || self::hasHeaders() || self::hasCookies() || self::hasCode() || self::hasMethod() || self::hasProtocol()) {
            self::send();
        }
    }
}
