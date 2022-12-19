<?php
namespace Riyu\Commands\Helpers;

class Color
{
    public static function red($text)
    {
        return "\033[31m$text\033[0m";
    }

    public static function green($text)
    {
        return "\033[32m$text\033[0m";
    }

    public static function yellow($text)
    {
        return "\033[33m$text\033[0m";
    }

    public static function blue($text)
    {
        return "\033[34m$text\033[0m";
    }

    public static function magenta($text)
    {
        return "\033[35m$text\033[0m";
    }

    public static function cyan($text)
    {
        return "\033[36m$text\033[0m";
    }

    public static function white($text)
    {
        return "\033[37m$text\033[0m";
    }
}