<?php

namespace Riyu\Database\Connection;

use Riyu\Database\Utils\ConnectionManager;
use PDO;
use PDOException;
use Riyu\App\Config;
use Riyu\Helpers\Errors\AppException;
use Riyu\Helpers\Errors\Message;

class Manager implements ConnectionManager
{
    /**
     * Connection for query
     * 
     * @var object
     */
    private $connection;

    /**
     * Set Connection for query
     * 
     * @return this
     */
    public function __construct($connection)
    {
        try {
            $this->connection = $connection;
        } catch (\Throwable $th) {
            $config = Config::get('app');
            if ($config['debug']) {
                echo $th->getMessage();
            } else {
                throw new AppException(Message::exception(1, $th->getMessage()));
            }
        }
    }

    /**
     * Execute query for select all
     * 
     * @param string $query
     * @param array $options
     * @return array
     */
    public function queryAll($query, $options = null)
    {
        try {
            $stmt = $this->connection->prepare($query);
            if (!is_null($options)) {
                $this->bindValue($stmt, $query, $options);
            }
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (\Throwable $th) {
            $config = Config::get('app');
            if ($config['debug']) {
                echo $th->getMessage();
            } else {
                throw new AppException(Message::exception(1, $th->getMessage()));
            }
        }
    }

    /**
     * Execute query for select get
     * 
     * @param string $query
     * @param array $options
     * @return array
     */
    public function queryGet($query, $options = null)
    {
        try {
            $stmt = $this->connection->prepare($query);
            if (!is_null($options)) {
                $this->bindValue($stmt, $query, $options);
            }
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $th) {
            $config = Config::get('app');
            if ($config['debug']) {
                echo $th->getMessage();
            } else {
                throw new AppException(Message::exception(1, $th->getMessage()));
            }
        }
    }

    /**
     * Execute query for select first
     * 
     * @param string $query
     * @param array $options
     * @return object
     */
    public function queryFirst($query, $options = null)
    {
        try {
            $stmt = $this->connection->prepare($query);
            if (!is_null($options)) {
                $this->bindValue($stmt, $query, $options);
            }
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_OBJ);
        } catch (PDOException $th) {
            $config = Config::get('app');
            if ($config['debug']) {
                echo $th->getMessage();
            } else {
                throw new AppException(Message::exception(1, $th->getMessage()));
            }
        }
    }

    /**
     * Execute query for insert update delete
     * 
     * @param string $query
     * @param array $options
     * @return mixed
     */
    public function execute($query, $options = null)
    {
        try {
            $stmt = $this->connection->prepare($query);
            if (!is_null($options)) {
                $this->bindValue($stmt, $query, $options);
            }
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_OBJ);
        } catch (\Throwable $th) {
            $config = Config::get('app');
            if ($config['debug']) {
                echo $th->getMessage();
            } else {
                throw new AppException(Message::exception(1, $th->getMessage()));
            }
        }
    }

    public function exec($query, $options = null)
    {
        try {
            $stmt = $this->connection->prepare($query);
            if (!is_null($options)) {
                $this->bindValue($stmt, $query, $options);
            }
            $stmt->execute();
            return $stmt->rowCount();
        } catch (\Throwable $th) {
            $config = Config::get('app');
            if ($config['debug']) {
                echo $th->getMessage();
            } else {
                throw new AppException(Message::exception(1, $th->getMessage()));
            }
        }
    }

    /**
     * Execute query for count
     * 
     * @param string $query
     * @param array $options
     * @return int
     */
    public function count($query, $options = null)
    {
        try {
            $stmt = $this->connection->prepare($query);
            if (!is_null($options)) {
                $this->bindValue($stmt, $query, $options);
            }
            $stmt->execute();
            return $stmt->rowCount();
        } catch (\Throwable $th) {
            $config = Config::get('app');
            if ($config['debug']) {
                echo $th->getMessage();
            } else {
                throw new AppException(Message::exception(1, $th->getMessage()));
            }
        }
    }

    /**
     * Execute query for count
     * 
     * @param string $query
     * @param array $options
     * @return int
     */
    public function queryCount($query, $options = null)
    {
        try {
            $stmt = $this->connection->prepare($query);
            if (!is_null($options)) {
                $this->bindValue($stmt, $query, $options);
            }
            $stmt->execute();
            return $stmt->rowCount();
        } catch (\Throwable $th) {
            $config = Config::get('app');
            if ($config['debug']) {
                echo $th->getMessage();
            } else {
                throw new AppException(Message::exception(1, $th->getMessage()));
            }
        }
    }

    /**
     * Bind value for query
     * 
     * @param object $stmt
     * @param string $query
     * @param array $options
     * @return void
     */
    public function bindValue($stmt, $query, $options)
    {
        try {
            foreach ($options as $key => $value) {
                switch ($options) {
                    case is_int($value):
                        $stmt->bindValue($key, $value, PDO::PARAM_INT);
                        break;
                    case is_bool($value):
                        $stmt->bindValue($key, $value, PDO::PARAM_BOOL);
                        break;
                    case is_null($value):
                        $stmt->bindValue($key, $value, PDO::PARAM_NULL);
                        break;
                    default:
                        $stmt->bindValue($key, $value, PDO::PARAM_STR);
                        break;
                }
            }
        } catch (\Throwable $th) {
            $config = Config::get('app');
            if ($config['debug']) {
                echo $th->getMessage();
            } else {
                throw new AppException(Message::exception(1, $th->getMessage()));
            }
        }
    }
}
