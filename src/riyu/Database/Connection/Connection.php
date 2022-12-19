<?php

namespace Riyu\Database\Connection;

use PDO;
use PDOException;
use Riyu\Helpers\Errors\AppException;
use Riyu\Helpers\Errors\Message;
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
        'driver', 'host', 'username', 'password', 'dbname', 'charset', 'port'
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
        Storage::setConfig($data);
        GlobalStorage::set('db_config', $data);
        GlobalStorage::set('db', $config);
    }
}
