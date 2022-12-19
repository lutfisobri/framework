<?php 
    namespace Riyu\Helpers\Errors;

    class Message
    {
        protected static $connection = [
            1 => 'Connection is not available',
            2 => 'Database not found',
            3 => 'Database cannot be connected',
        ];

        protected static $message = [
            4 => 'Method %s not found',
            5 => 'Table %s not found',
            6 => 'Column %s not found',
            7 => 'Column %s not found in table %s',
            8 => 'Table %s not found in database %s',
        ];

        protected static $routing = [
            100 => 'Route %s not found',
            101 => 'Route %s not found in %s',
            102 => 'Route %s not found in %s',
        ];

        protected static $callback = [
            500 => 'Callback not found',
            501 => 'Callback %s not found in %s',
            505 => 'Callback %s not found in %s',
        ];

        public static function Routing($code, $data)
        {
            return sprintf(self::$routing[$code], $data);
        }

        public static function Callback($code, $data = [])
        {
            if (array_key_exists($code, self::$callback)) {
                return sprintf(self::$callback[$code], $data);
            }
            // return sprintf(self::$callback[$code], $data);
        }

        public static function exception($code, $args = null)
        {
            if (is_null($args)) {
                if (array_key_exists($code, self::$message)) {
                    return self::$message[$code];
                }

                if (array_key_exists($code, self::$connection)) {
                    return self::$connection[$code];
                }

                if (array_key_exists($code, self::$routing)) {
                    return self::$routing[$code];
                }

                if (array_key_exists($code, self::$callback)) {
                    return self::$callback[$code];
                }
            } else {
                if (array_key_exists($code, self::$message)) {
                    return sprintf(self::$message[$code], $args);
                }

                if (array_key_exists($code, self::$connection)) {
                    return sprintf(self::$connection[$code], $args);
                }

                if (array_key_exists($code, self::$routing)) {
                    return sprintf(self::$routing[$code], $args);
                }

                if (array_key_exists($code, self::$callback)) {
                    return sprintf(self::$callback[$code], $args);
                }
            }
            return 'code not found';
        }

        public static function connectionError($code, $args = null)
        {
            if (is_null($args)) {
                return self::$connection[$code];
            } else {
                return sprintf(self::$connection[$code], $args);
            }
        }

        public static function queryError($code)
        {
            return self::$message[$code];
        }

        public static function __callStatic($method, $args)
        {
            return (new static)->$method(...$args);
        }

        public function __call($method, $args)
        {
            return call_user_func_array([new self, $method], $args);
        }
    }
?>