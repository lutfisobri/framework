<?php 
namespace Riyu\Database\Connection;

use PDO;
use Riyu\App\Config;
use Riyu\Helpers\Errors\AppException;
use Riyu\Helpers\Storage\GlobalStorage;

class Event
{
    /**
     * Connection for database
     * 
     * @var pdo
     */
    protected $connection;

    /**
     * Raw config for database
     * 
     * @var string
     */
    private $config;

    /**
     * Dsn for database
     * 
     * @var string
     */
    private $dsn;

    /**
     * Default options for database
     * 
     * @var array
     */
    private $options = [
        PDO::ATTR_CASE => PDO::CASE_NATURAL,
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_ORACLE_NULLS => PDO::NULL_NATURAL,
        PDO::ATTR_STRINGIFY_FETCHES => false,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];

    /**
     * Constructor for Event
     * 
     * @return void
     */
    public function __construct()
    {
        if (Config::has('database')) {
            $this->config = Config::get('database');
        } else if (GlobalStorage::has('database')) {
            $this->config = GlobalStorage::get('db');
        } else {
            throw new AppException("Database config not found, please check your config file");
        }
    }

    /**
     * Connect to database
     * 
     * @return object pdo
     */
    public static function connect()
    {
        $instance = new static;
        $instance->validateConfig();
        $instance->setDsn();
        $instance->setConnection();
        return $instance->connection;
    }

    /**
     * Set dsn for database
     * 
     * @return void
     */
    public function setDsn()
    {
        $dsn = '';
        $dsn .= $this->config['driver'] . ':';
        $dsn .= 'host=' . $this->config['host'] . ';';
        $dsn .= 'port=' . $this->config['port'] . ';';
        $dsn .= 'dbname=' . $this->config['database'] . ';';
        $dsn .= 'charset=' . $this->config['charset'] . ';';

        $this->dsn = $dsn;
    }

    /**
     * Set connection for database
     * 
     * @return void
     */
    public function setConnection()
    {
        $username = $this->config['username'];
        $password = $this->config['password'];
        $options = $this->options;
        
        if (isset($this->config['options'])) {
            $options = $this->config['options'];
        }

        try {
            $this->connection = new PDO($this->dsn, $username, $password, $options);
        } catch (\Throwable $th) {
            throw new \ErrorException($th->getMessage(), $th->getCode(), 1, $th->getFile(), $th->getLine(), $th->getPrevious());
        }
    }

    public function raw()
    {
        $this->validateConfig();

        $dsn = '';
        $dsn .= $this->config['driver'] . ':';
        $dsn .= 'host=' . $this->config['host'] . ';';
        $dsn .= 'port=' . $this->config['port'] . ';';
        $dsn .= 'charset=' . $this->config['charset'] . ';';
        $username = $this->config['username'];
        $password = $this->config['password'];
        $options = $this->options;

        if (isset($this->config['options'])) {
            $options = $this->config['options'];
        }

        try {
            $connection = new PDO($dsn, $username, $password, $options);
        } catch (\Throwable $th) {
            throw new \ErrorException($th->getMessage(), $th->getCode(), 1, $th->getFile(), $th->getLine(), $th->getPrevious());
        }

        return $connection;
    }

    private function validateConfig()
    {
        $config = Config::get('path');
        $config = $config . 'config.php';

        if (!isset($this->config['driver'])) {
            throw new \ErrorException("Driver not found in config file: $config", 1, 1, $config, 0, null);
        }

        if (!isset($this->config['host'])) {
            throw new \ErrorException("Host not found in config file: $config", 1, 1, $config, 0, null);
        }

        if (!isset($this->config['port'])) {
            throw new \ErrorException("Port not found in config file: $config", 1, 1, $config, 0, null);
        }

        if (!isset($this->config['charset'])) {
            throw new \ErrorException("Charset not found in config file: $config", 1, 1, $config, 0, null);
        }

        if (!isset($this->config['username'])) {
            throw new \ErrorException("Username not found in config file: $config", 1, 1, $config, 0, null);
        }

        if (!isset($this->config['password'])) {
            throw new \ErrorException("Password not found in config file: $config", 1, 1, $config, 0, null);
        }

        if (!isset($this->config['database'])) {
            throw new \ErrorException("Database not found in config file: $config", 1, 1, $config, 0, null);
        }
    }
}