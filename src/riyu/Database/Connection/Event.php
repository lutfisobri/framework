<?php 
namespace Riyu\Database\Connection;

use PDO;
use PDOException;
use Riyu\App\Config;
use Riyu\Helpers\Errors\AppException;
use Riyu\Helpers\Errors\Message;
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
     * Constructor for Event
     * 
     * @return void
     */
    public function __construct()
    {
        $this->config = GlobalStorage::get('db_config');
    }

    /**
     * Connect to database
     * 
     * @return object pdo
     */
    public static function connect()
    {
        $instance = new static;
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
        $config = Config::get('database');
        $this->config = $config;
        $this->dsn = $this->config['driver'] . ':host=' . $this->config['host'] . ';dbname=' . $this->config['dbname'] . ';charset=' . $this->config['charset'] . ';port=' . $this->config['port'];
    }

    /**
     * Set connection for database
     * 
     * @return void
     */
    public function setConnection()
    {
        try {
            $this->connection = new PDO($this->dsn, $this->config['username'], $this->config['password']);
            return $this->connection;
        } catch (PDOException $e) {
            new AppException(Message::exception(1, $e->getMessage()), 1);
        }
    }

    public function raw()
    {
        $dsn = $this->config['driver'] . ':host=' . $this->config['host'] . ';charset=' . $this->config['charset'] . ';port=' . $this->config['port'];
        $this->setDsn();
        $connection = $this->dsn;
        $connection = new PDO($dsn, $this->config[2], $this->config[3]);
        $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $connection;
    }
}