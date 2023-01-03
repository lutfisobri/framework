<?php 
namespace Riyu\Database\Connection;

use PDO;
use PDOException;
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
            throw new AppException("Database config not found");
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

        $this->connection = new PDO($this->dsn, $username, $password, $options);
        return $this->connection;
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

        $connection = new PDO($dsn, $username, $password, $options);
        return $connection;
    }

    private function validateConfig()
    {
        if (!isset($this->config['driver'])) {
            throw new AppException("Driver not found");
        }

        if (!isset($this->config['host'])) {
            throw new AppException("Host not found");
        }

        if (!isset($this->config['port'])) {
            throw new AppException("Port not found");
        }

        if (!isset($this->config['charset'])) {
            throw new AppException("Charset not found");
        }

        if (!isset($this->config['username'])) {
            throw new AppException("Username not found");
        }

        if (!isset($this->config['password'])) {
            throw new AppException("Password not found");
        }

        if (!isset($this->config['database'])) {
            throw new AppException("Database not found");
        }
    }
}