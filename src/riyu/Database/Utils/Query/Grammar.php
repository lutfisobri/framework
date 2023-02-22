<?php

namespace Riyu\Database\Utils\Query;

class Grammar
{
    /**
     * @var string
     */
    protected $insert;

    /**
     * @var array
     */
    protected $joins = [];

    /**
     * @var array
     */
    protected $where = [];

    /**
     * @var bool
     */
    protected $isDelete;

    /**
     * @var array
     */
    protected $groups;

    /**
     * @var array
     */
    protected $orders;

    /**
     * @var string
     */
    protected $update;

    /**
     * @var array
     */
    protected $having;

    /**
     * @var array
     */
    protected $selects;

    /**
     * @var string
     */
    protected $from;

    /**
     * @var string
     */
    protected $limit;

    /**
     * @var string
     */
    protected $offset;

    /**
     * @var string
     */
    protected $select;
    /**
     * @var string
     */
    protected $order;

    /**
     * @var string
     */
    protected $table;

    /**
     * @var array
     */
    protected $values = [];

    /**
     * @var array
     */
    protected $columns = [];

    /**
     * @var array
     */
    protected $bindings = [];

    /**
     * @var array
     */
    protected $set = [];

    /**
     * @var array
     */
    protected $data = [];

    /**
     * @var array
     */
    protected $sets = [];

    /**
     * @var object|string|null
     */
    protected $model;

    /**
     * @var string
     */
    protected $primaryKey;

    /**
     * @var array
     */
    protected $fillable = [];

    /**
     * @var string
     */
    protected $prefix;

    /**
     * @var array
     */
    protected $timestamp = [];

    /**
     * @var object|string|null
     */
    protected $connection;

    /**
     * @var \Riyu\Database\Connection\Manager
     */
    protected $manager;

    public function __construct()
    {
        $connection = new \Riyu\Database\Connection\Event;
        $this->connection = $connection->connect();
        $this->manager = new \Riyu\Database\Connection\Manager($this->connection);
    }

    /**
     * Set config for query builder
     * 
     * @param array $config
     * @return void
     */
    public function setConfig(array $config)
    {
        $table = $config['table'];
        $fillable = $config['fillable'];
        $primaryKey = $config['primaryKey'];
        $this->table = $table;
        $this->fillable = $fillable;
        $this->primaryKey = $primaryKey;
    }

    /**
     * Set Timestamp
     * 
     * @param array $column
     * @return $this
     */
    public function setTimestamp(array $column)
    {
        $this->timestamp = $column;
        return $this;
    }

    /**
     * Compile query insert
     * 
     * @return string
     */
    public function buildInsert()
    {
        $table = $this->buildTable();
        $columns = $this->buildColumns();
        $values = $this->buildValues();
        $query = 'INSERT INTO ' . $table . ' ' . $columns . ' VALUES ' . $values . ';';
        return $query;
    }

    /**
     * Compile query select
     * 
     * @return string
     */
    public function buildSelect()
    {
        $select = $this->buildSelects();
        $from = $this->buildFrom();
        $join = $this->buildJoins();
        $where = $this->buildWheres();
        $group = $this->buildGroups();
        $order = $this->buildOrders();
        $limit = $this->buildLimit();
        $offset = $this->buildOffset();
        $having = $this->buildHaving();
        $query = $select . $from . $join . $where . $group . $having . $order . $limit . $offset . ';';
        return $query;
    }

    /**
     * Compile query update
     * 
     * @return string
     */
    public function buildUpdate()
    {
        $table = $this->buildTable();
        $set = $this->buildSet();
        $where = $this->buildWheres();
        $query = 'UPDATE `' . $table . '` ' . $set . ' ' . $where . ';';
        return $query;
    }

    /**
     * Compile query delete
     * 
     * @return string
     */
    public function buildDelete()
    {
        $table = $this->buildTable();
        $where = $this->buildWheres();
        $query = 'DELETE FROM `' . $table . '` ' . $where;
        return $query;
    }

    /**
     * Compile select
     * 
     * @return string
     */
    public function buildSelects()
    {
        $select = $this->selects;
        if (empty($select)) {
            return 'SELECT * ';
        }
        return 'SELECT ' . $select . ' ';
    }


    /**
     * Compile query truncate
     * 
     * @return string
     */
    public function buildTruncate()
    {
        $table = $this->buildTable();
        $query = 'TRUNCATE TABLE ' . $table;
        return $query;
    }

    /**
     * Compile from
     * 
     * @return string
     */
    public function buildFrom()
    {
        $from = $this->from;
        if (empty($from)) {
            $from = $this->table;
        }
        return 'FROM `' . $from . '` ';
    }

    /**
     * Compile joins table
     * 
     * @return string
     */
    public function buildJoins()
    {
        $join = $this->joins;
        $sql = '';
        foreach ($join as $key => $value) {
            $sql .= $value['type'] . ' JOIN `' . $value['table'] . '` ON ' . $value['first'] . ' ' . $value['operator'] . ' ' . $value['second'] . ' ';
        }
        if (empty($join)) {
            return '';
        }
        return $sql . ' ';
    }

    /**
     * Compile where
     * 
     * @return string
     */
    public function buildWheres()
    {
        $where = $this->where;

        if (count($where) == 0) {
            return '';
        }

        if (count($where) == 1) {
            if (!is_array($where[0])) {
                return 'WHERE ' . $where[0] . ' ';
            } else {
                return 'WHERE ' . $where[0]['column'] . ' ' . $where[0]['operator'] . ' ' . $where[0]['value'] . ' ';
            }
        }
        
        $query = '';
        
        foreach ($where as $key => $value) {
            if ($key == 0) {
                if (!is_array($value)) {
                    $query .= $value . ' ';
                } else {
                    $query .= '' . $value['column'] . ' ' . $value['operator'] . ' ' . $value['value'] . ' ';
                }
            } else {
                if (!is_array($value)) {
                    $query .= $value . ' ';
                } else {
                    $query .= $value['boolean'] . ' ' . $value['column'] . ' ' . $value['operator'] . ' ' . $value['value'] . ' ';
                }
            }
        }

        if ($query == '') {
            return '';
        }

        return ' WHERE ' . $query;
    }

    /**
     * Compile query group by
     * 
     * @return string
     */
    public function buildGroups()
    {
        $group = $this->groups;
        $sql = '';
        if (empty($group) || count($group) == 0) {
            return '';
        }
        foreach ($group as $key => $value) {
            $sql .= $value . ' ';
        }
        if ($sql == '') {
            return '';
        }
        return 'GROUP BY ' . $sql . ' ';
    }

    /**
     * Compile query having
     * 
     * @return string
     */
    public function buildHaving()
    {
        $having = $this->having;
        $sql = '';
        if (empty($having) || count($having) == 0) {
            return '';
        }
        foreach ($having as $key => $value) {
            $sql .= $value['type'] . ' ' . $value['column'] . ' ' . $value['operator'] . ' ' . $value['value'];
        }
        if ($sql == '') {
            return '';
        }
        return 'HAVING ' . $sql . ' ';
    }

    /**
     * Compile query order by
     * 
     * @return string
     */
    public function buildOrders()
    {
        $order = $this->orders;
        $sql = '';
        if (empty($order) || count($order) == 0) {
            return '';
        }
        foreach ($order as $key => $value) {
            $sql .= $value . ' ';
        }
        if ($sql == '') {
            return '';
        }
        return 'ORDER BY ' . $sql . ' ';
    }

    /**
     * Compile query limit
     * 
     * @return string
     */
    public function buildLimit()
    {
        $limit = $this->limit;
        if (empty($limit)) {
            return '';
        }
        return 'LIMIT ' . $limit . ' ';
    }

    /**
     * Compile query offset
     * 
     * @return string
     */
    public function buildOffset()
    {
        $offset = $this->offset;
        if (empty($offset)) {
            return '';
        }
        return 'OFFSET ' . $offset . ' ';
    }

    /**
     * Compile query table
     * 
     * @return string
     */
    public function buildTable()
    {
        $table = $this->table;
        if (empty($table)) {
            return '';
        }
        return $table;
    }

    /**
     * Compile query insert columns
     * 
     * @return string
     */
    public function buildColumns()
    {
        $columns = $this->columns;

        if (count($columns) == 0) {
            return '';
        }

        $query = '(';
        foreach ($columns as $column) {
            $query .= "`" . $column . "`, ";
        }


        if (count($this->timestamp) > 0) {
            if (isset($this->timestamp['created_at'])) {
                $query .= "`" . $this->timestamp['created_at'] . "`, ";
            }
            if (isset($this->timestamp['updated_at'])) {
                $query .= "`" . $this->timestamp['updated_at'] . "`, ";
            }
        }

        $query = substr($query, 0, -2);
        $query .= ')';
        return $query;
    }

    /**
     * Compile query insert values
     * 
     * @return string
     */
    public function buildValues()
    {
        $values = $this->values;

        if (count($values) == 0) {
            return '';
        }

        $query = '(';
        foreach ($values as $value) {
            $query .= $value . ", ";
        }

        if (count($this->timestamp) > 0) {
            $date = date('Y-m-d H:i:s');
            $query .= $date . ", ";
            $query .= $date . ", ";
        }

        $query = substr($query, 0, -2);
        $query .= ')';
        return $query;
    }

    /**
     * Compile query update set
     * 
     * @return string
     */
    public function buildSet()
    {
        $set = $this->set;

        if (count($set) == 0) {
            return '';
        }

        $query = 'SET ';
        foreach ($set as $value) {
            $query .= $value . ", ";
        }

        if (count($this->timestamp) > 0) {
            if (isset($this->timestamp['updated_at'])) {
                $date = date('Y-m-d H:i:s');
                $query .= "`" . $this->timestamp['updated_at'] . "` = " . $date . ", ";
            }
        }

        $query = substr($query, 0, -2);

        return $query;
    }
}
