<?php
namespace Riyu\Database\Schema;

use Riyu\Database\Schema\Traits\Date;
use Riyu\Database\Schema\Traits\Numeric;
use Riyu\Database\Schema\Traits\Text;

/**
 * Blueprint
 * 
 * Blueprint is a class that will help you to create a table
 * 
 * @method 
 * 
 * @package Riyu\Database\Schema
 */
class Blueprint
{
    use Date, Text, Numeric;

    public $name;

    public $columns = [];

    public $parameters = [];

    public $create;

    public function __construct($name)
    {
        $this->name = $name;
    }

    public function addColumn($type, $name, $parameters = [])
    {
        $this->columns[] = compact('type', 'name', 'parameters');
    }

    public function id($column = 'id')
    {
        $this->addColumn('int', $column, ['length' => 11, 'autoIncrement' => true, 'primary' => true]);
    }

    public function unique()
    {
        $this->columns[count($this->columns) - 1]['parameters']['unique'] = true;
        return $this;
    }

    public function nullable(bool $nullable = true)
    {
        $this->columns[count($this->columns) - 1]['parameters']['nullable'] = $nullable;
        return $this;
    }

    public function default($value)
    {
        $this->columns[count($this->columns) - 1]['parameters']['default'] = $value;
        return $this;
    }

    public function primary()
    {
        $this->columns[count($this->columns) - 1]['parameters']['primary'] = true;
        return $this;
    }

    public function autoIncrement()
    {
        $this->columns[count($this->columns) - 1]['parameters']['autoIncrement'] = true;
        return $this;
    }

    public function increment()
    {
        $this->columns[count($this->columns) - 1]['parameters']['autoIncrement'] = true;
        return $this;
    }

    public function softDeletes()
    {
        $this->addColumn('datetime', 'deleted_at', ['nullable' => true]);
        return $this;
    }

    public function timestamps()
    {
        $this->addColumn('datetime', 'created_at', ['nullable' => true]);
        $this->addColumn('datetime', 'updated_at', ['nullable' => true]);
        return $this;
    }

    public function rememberToken()
    {
        $this->addColumn('varchar', 'remember_token', ['length' => 100, 'nullable' => true]);
        return $this;
    }

    public function engine($engine)
    {
        $this->parameters['engine'] = $engine;
        return $this;
    }

    public function charset($charset)
    {
        $this->parameters['charset'] = $charset;
        return $this;
    }

    public function collation($collation)
    {
        $this->parameters['collation'] = $collation;
        return $this;
    }

    public function create()
    {
        $this->create = true;
    }

    public function foreign($column)
    {
        $this->columns[count($this->columns) - 1]['parameters']['foreign'] = $column;
        return $this;
    }

    public function references($table)
    {
        $this->columns[count($this->columns) - 1]['parameters']['references'] = $table;
        return $this;
    }

    public function on($table)
    {
        $this->columns[count($this->columns) - 1]['parameters']['references'] = $table;
        return $this;
    }

    public function onDelete($action = 'cascade')
    {
        $this->columns[count($this->columns) - 1]['parameters']['onDelete'] = $action;
        return $this;
    }

    public function onUpdate($action = 'cascade')
    {
        $this->columns[count($this->columns) - 1]['parameters']['onUpdate'] = $action;
        return $this;
    }

    public function cascade()
    {
        $this->columns[count($this->columns) - 1]['parameters']['onDelete'] = 'cascade';
        $this->columns[count($this->columns) - 1]['parameters']['onUpdate'] = 'cascade';
        return $this;
    }
}