<?php
namespace Riyu\Database\Utils;

trait Event
{
    public static function has()
    {
        print_r(get_called_class());
    }
}