<?php

namespace Riyu\Database\Connection;

use Riyu\Helpers\Storage\GlobalStorage;

class Connection
{
    /**
     * DSN for connection
     * 
     * @var string
     */
    protected $value;

    /**
     * Default connection
     * 
     */
    private $pdoc = [
        'driver', 'host', 'username', 'password', 'database', 'charset', 'port'
    ];

    /**
     * Set connection
     *
     * @param array $config
     * @return void
     */
    public static function config(array $config)
    {
        $data = [];
        foreach ((new static)->pdoc as $key => $value) {
            if (array_key_exists($value, $config)) {
                $data[] = $config[$value];
            }
        }
        GlobalStorage::set('db', $config);
    }
}
