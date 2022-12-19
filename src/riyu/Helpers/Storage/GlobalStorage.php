<?php

namespace Riyu\Helpers\Storage;

abstract class GlobalStorage
{
    /**
     * Storage for global data
     * 
     * @var array
     */
    protected static $storage = [];

    /**
     * Set data to storage
     * 
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public static function set($key, $value)
    {
        self::$storage = array_merge(self::$storage, [$key => $value]);
    }

    /**
     * Get data from storage
     * 
     * @param string $key
     * @return mixed
     */
    public static function get($key)
    {
        if (array_key_exists($key, self::$storage)) {
            return self::$storage[$key];
        }
        return null;
    }

    /**
     * Get all data from storage
     * 
     * @return array
     */
    public static function all()
    {
        return self::$storage;
    }

    /**
     * Check if key exists in storage
     * 
     * @param string $key
     * @return bool
     */
    public static function has($key)
    {
        return array_key_exists($key, self::$storage);
    }

    /**
     * Remove data from storage
     * 
     * @param string $key
     * @return void
     */
    public static function remove($key)
    {
        if (self::has($key)) {
            unset(self::$storage[$key]);
        }
    }

    /**
     * Clear storage
     * 
     * @return void
     */
    public static function clear()
    {
        self::$storage = [];
    }

    /**
     * Count data in storage
     * 
     * @return int
     */
    public static function count()
    {
        return count(self::$storage);
    }

    /**
     * Get all keys from storage
     * 
     * @return array
     */
    public static function keys()
    {
        return array_keys(self::$storage);
    }

    /**
     * Get all values from storage
     * 
     * @return array
     */
    public static function values()
    {
        return array_values(self::$storage);
    }

    /**
     * Merge array to storage
     * 
     * @param array $array
     * @return void
     */
    public static function merge($array)
    {
        self::$storage = array_merge(self::$storage, $array);
    }

    /**
     * Replace array to storage
     * 
     * @param array $array
     * @return void
     */
    public static function replace($array)
    {
        self::$storage = array_replace(self::$storage, $array);
    }

    /**
     * Push value to array in storage
     * 
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public static function push($key, $value)
    {
        if (self::has($key)) {
            $array = self::get($key);
            if (is_array($array)) {
                array_push($array, $value);
                self::set($key, $array);
            }
        }
    }

    /**
     * Pop value from array in storage
     * 
     * @param string $key
     * @return void
     */
    public static function pop($key)
    {
        if (self::has($key)) {
            $array = self::get($key);
            if (is_array($array)) {
                array_pop($array);
                self::set($key, $array);
            }
        }
    }

    /**
     * Shift value from array in storage
     * 
     * @param string $key
     * @return void
     */
    public static function shift($key)
    {
        if (self::has($key)) {
            $array = self::get($key);
            if (is_array($array)) {
                array_shift($array);
                self::set($key, $array);
            }
        }
    }

    /**
     * Unshift value to array in storage
     * 
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public static function unshift($key, $value)
    {
        if (self::has($key)) {
            $array = self::get($key);
            if (is_array($array)) {
                array_unshift($array, $value);
                self::set($key, $array);
            }
        }
    }
}
