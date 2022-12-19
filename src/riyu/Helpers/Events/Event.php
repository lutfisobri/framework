<?php

namespace Riyu\Helpers\Events;

class Event
{
    public static function on($event, $callback)
    {
        $events = self::getEvents();
        $events[$event][] = $callback;
        self::setEvents($events);
    }

    public static function off($event, $callback)
    {
        $events = self::getEvents();
        if (isset($events[$event])) {
            foreach ($events[$event] as $key => $value) {
                if ($value == $callback) {
                    unset($events[$event][$key]);
                }
            }
        }
        self::setEvents($events);
    }

    public static function trigger($event, $data = null)
    {
        $events = self::getEvents();
        if (isset($events[$event])) {
            foreach ($events[$event] as $key => $value) {
                if (is_callable($value)) {
                    $value($data);
                }
            }
        }
    }

    private static function getEvents()
    {
        return $_SESSION['events'] ?? [];
    }

    private static function setEvents($events)
    {
        $_SESSION['events'] = $events;
    }

    public static function clear()
    {
        unset($_SESSION['events']);
    }

    public static function get($event)
    {
        $events = self::getEvents();
        return $events[$event] ?? [];
    }

    public static function __callStatic($name, $arguments)
    {
        if (method_exists(self::class, $name)) {
            return self::$name(...$arguments);
        }
    }

    public function __call($name, $arguments)
    {
        if (method_exists(self::class, $name)) {
            return self::$name(...$arguments);
        }
    }
}
