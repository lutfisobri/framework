<?php
namespace Riyu\Http;

trait Input
{
    public static function get($key = null)
    {
        if ($key == null) {
            return $_GET;
        } else {
            return $_GET[$key];
        }
    }

    public static function post($key = null)
    {
        if ($key == null) {
            return $_POST;
        } else {
            return $_POST[$key];
        }
    }

    public static function request($key = null)
    {
        if ($key == null) {
            return $_REQUEST;
        } else {
            return $_REQUEST[$key];
        }
    }

    public static function server($key = null)
    {
        if ($key == null) {
            return $_SERVER;
        } else {
            return $_SERVER[$key];
        }
    }

    public static function cookie($key = null)
    {
        if ($key == null) {
            return $_COOKIE;
        } else {
            return $_COOKIE[$key];
        }
    }

    public static function session($key = null)
    {
        if ($key == null) {
            return $_SESSION;
        } else {
            return $_SESSION[$key];
        }
    }

    public static function file($key = null)
    {
        if ($key == null) {
            return $_FILES;
        } else {
            return $_FILES[$key];
        }
    }

}