<?php
namespace Riyu\Database\Schema;

use Riyu\Database\Connection\Event;
use Riyu\Helpers\Storage\GlobalStorage;

class Grammar
{
    public $table;

    public function showTables()
    {
        $connection = new Event();
        $connection = $connection->connect();
        $sql = "SHOW TABLES";
        $stmt = $connection->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return $result;
    }

    public function checkDatabase()
    {
        $database = GlobalStorage::get('db')['dbname'];
        $connection = new Event();
        $connection = $connection->raw();
        $sql = "SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '$database'";
        $stmt = $connection->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return $result;
    }

    public function execute(string $sql, string $name)
    {
        $db = $this->checkDatabase();
        if (count($db) == 0) {
            $connection = new Event();
            $connection = $connection->raw();
            $query = "CREATE DATABASE IF NOT EXISTS " . GlobalStorage::get('db')['dbname'];
            $connection->exec($query);
        }

        $table = $this->showTables();

        if (count($table) == 0) {
            $connection = new Event();
            $connection = $connection->connect();
            $connection->exec($sql);
            return $name . " created successfully";
        }

        $table = array_values($table);
        $table = array_map(function ($value) {
            return $value['Tables_in_' . GlobalStorage::get('db')['dbname']];
        }, $table);
        
        $table = array_filter($table, function ($value) use ($name) {
            return $value == $name;
        });

        if (count($table) == 0) {
            $connection = new Event();
            $connection = $connection->connect();
            $stmt = $connection->prepare($sql);
            $stmt->execute();
            return $name . " created successfully";
        }

        return $name . " already exists";
    }

    public function delete($sql)
    {
        $table = $this->showTables();
        $table = array_values($table);
        $table = array_map(function ($value) {
            return $value['Tables_in_' . GlobalStorage::get('db')['dbname']];
        }, $table);

        $table = array_filter($table, function ($value) use ($sql) {
            $sql = explode(' ', $sql);
            $sql = $sql[2];
            return $value == $sql;
        });

        if (count($table) == 0) {
            $sql = explode(' ', $sql);
            $table = $sql[2];
            return $table . " does not exists";
        }

        $connection = new Event();
        $connection = $connection->connect();
        $stmt = $connection->prepare($sql);
        $stmt->execute();

        $table = implode($table);
        return $table . " deleted successfully";
    }

    public function compileCreate(Blueprint $blueprint)
    {
        $sql = "CREATE TABLE IF NOT EXISTS {$blueprint->name} (";
        $columns = [];

        foreach ($blueprint->columns as $column) {
            $columns[] = $this->compileColumn($column);
        }

        $sql .= implode(', ', $columns);

        foreach ($blueprint->columns as $column) {
            $sql .= ', ' . $this->compilePrimaryKey($column);
        }
        
        if (isset($blueprint->parameters['engine'])) {
            $sql .= " ENGINE={$blueprint->parameters['engine']}, ";
        }

        if (isset($blueprint->parameters['charset'])) {
            $sql .= " CHARSET={$blueprint->parameters['charset']}, ";
        }

        if (isset($blueprint->parameters['collation'])) {
            $sql .= " COLLATE={$blueprint->parameters['collation']}, ";
        }

        // check foreign key
        $sql .= $this->compileForeignKey($blueprint->columns);

        $sql = trim($sql, ', ');
        $sql .= ')';

        return $sql;
    }

    public function compileColumn($column)
    {
        $sql = "{$column['name']} {$column['type']}";
        if (isset($column['parameters']['length'])) {
            $sql .= "({$column['parameters']['length']})";
        }
        if (isset($column['parameters']['autoIncrement']) && $column['parameters']['autoIncrement']) {
            $sql .= ' AUTO_INCREMENT';
        }
        if (isset($column['parameters']['unique']) && $column['parameters']['unique']) {
            $sql .= ' UNIQUE';
        }
        if (isset($column['parameters']['nullable']) && $column['parameters']['nullable'] == true) {
            $sql .= ' NULL';
        } else {
            $sql .= ' NOT NULL';
        }
        if (isset($column['parameters']['default'])) {
            $sql .= " DEFAULT {$column['parameters']['default']}";
        }
        return $sql;
    }

    public function compilePrimaryKey($column)
    {
        if (! isset($column['parameters']['primary']) || ! $column['parameters']['primary']) {
            return '';
        }

        return "PRIMARY KEY ({$column['name']})";
    }

    public function compileForeignKey($column)
    {
        $sql = '';
        foreach ($column as $key => $value) {
            if (isset($value['parameters']['foreign'])) {
                $sql .= " FOREIGN KEY ({$value['name']}) REFERENCES {$value['parameters']['foreign']['table']}({$value['parameters']['foreign']['column']})";
            }
        }
        return $sql;
    }
    
    public function compileDrop(Blueprint $blueprint)
    {
        return "DROP TABLE {$blueprint->name}";
    }
}