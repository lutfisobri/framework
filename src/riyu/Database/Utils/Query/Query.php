<?php

namespace Riyu\Database\Utils\Query;

use Riyu\Helpers\Errors\AppException;

trait Query
{
    /**
     * Bindings for the query
     * 
     * @param string $key
     * @param mixed $value
     * @return $this
     */
    public function binding($key, $value)
    {
        $this->bindings[$key] = $value;
        return $this;
    }

    /**
     * Handle select query
     * 
     * @return $this
     */
    public function select($selects = ['*'])
    {
        $selects = is_array($selects) ? $selects : func_get_args();

        foreach ($selects as $key => $column) {
            if (is_string($key)) {
                $select[$key] = $column . " AS " . $key;
            } else {
                $select[] = $column;
            }
        }
        $this->selects = implode(", ", $select);
        $this->queryType = 'select';
        return $this;
    }

    /**
     * Handle insert query
     * 
     * @param mixed $insert
     * @return $this
     */
    public function insert($insert)
    {
        $insert = is_array($insert) ? $insert : func_get_args();

        foreach ($insert as $key => $value) {
            if (!in_array($key, $this->fillable)) {
                throw new AppException("The column {$key} is not fillable");
            }
            $this->columns[] = $key;
            $this->values[] = ":" . $key;
            $this->binding($key, $value);
        }

        $this->insert = $insert;
        $this->queryType = 'insert';
        return $this;
    }

    /**
     * Handle update query
     * 
     * @param mixed $update
     * @return $this
     */
    public function update($update)
    {
        $update = is_array($update) ? $update : func_get_args();

        foreach ($update as $key => $value) {
            if (!in_array($key, $this->fillable)) {
                throw new AppException("The column {$key} is not fillable");
            }
            if (is_string($key)) {
                $binding = "update" . count($this->set);
                $this->set[] = "`" . $key . "` = :" . $binding;
                $this->binding($binding, $value);
            } else {
                $binding = "update" . count($this->set);
                $this->set[] = "`" . $key . "` = :" . $binding;
                $this->binding($binding, $update[$value]);
            }
        }

        $this->update = $update;
        $this->queryType = 'update';
        return $this;
    }

    /**
     * Handle delete query
     * 
     * @return $this
     */
    public function delete()
    {
        $this->isDelete = true;
        $this->queryType = 'delete';
        return $this;
    }

    /**
     * Handle join query
     * 
     * @param string $table
     * @param string $first
     * @param string $operator
     * @param string $second
     * @param string $type
     * @return $this
     */
    public function join($table, $first, $operator = null, $second = null, $type = 'INNER')
    {
        // check if the operator is null
        // and set the operator to equal
        if (func_num_args() == 3) {
            list($second, $operator) = [$operator, '='];
        }

        if ($type != 'inner' && in_array($type, $this->verbJoin)) {
            if (func_get_args() == 4) {
                list($second, $operator) = [$operator, '='];
            }
        }

        // check if the value is null
        if (is_null($second)) {
            $operator = '=';
        }

        $this->joins[] = compact('table', 'first', 'operator', 'second', 'type');
        return $this;
    }

    /**
     * Handle left join query
     * 
     * @param string $table
     * @param string $first
     * @param string $operator
     * @param string $second
     * @return $this
     */
    public function leftjoin($table, $first, $operator = null, $second = null)
    {
        return $this->join($table, $first, $operator, $second, 'LEFT');
    }

    /**
     * Handle right join query
     * 
     * @param string $table
     * @param string $first
     * @param string $operator
     * @param string $second
     * @return $this
     */
    public function rightjoin($table, $first, $operator = null, $second = null)
    {
        return $this->join($table, $first, $operator, $second, 'RIGHT');
    }

    /**
     * Handle full join query
     * 
     * @param string $table
     * @param string $first
     * @param string $operator
     * @param string $second
     * @return $this
     */
    public function fulljoin($table, $first, $operator = null, $second = null)
    {
        return $this->join($table, $first, $operator, $second, 'FULL');
    }

    /**
     * Handle inner join query
     * 
     * @param string $table
     * @param string $first
     * @param string $operator
     * @param string $second
     * @return $this
     */
    public function innerJoin($table, $first, $operator = null, $second = null)
    {
        return $this->join($table, $first, $operator, $second, 'INNER');
    }

    /**
     * Handle outer join query
     * 
     * @param string $table
     * @param string $first
     * @param string $operator
     * @param string $second
     * @return $this
     */
    public function outerJoin($table, $first, $operator = null, $second = null)
    {
        return $this->join($table, $first, $operator, $second, 'OUTER');
    }

    /**
     * Handle where query
     * 
     * @param string $column
     * @param string $operator
     * @param string $value
     * @param string $boolean
     * @return $this
     */
    public function where($column, $operator = null, $value = null, $boolean = 'AND')
    {
        if (func_num_args() == 1) {
            $this->where[] = $column;
            return $this;
        }

        // check if the operator is null
        if (func_num_args() == 2) {
            list($value, $operator) = [$operator, '='];
        }

        // check if the value is null
        if (is_null($value)) {
            list($value, $operator) = [$operator, '='];
        }

        $binding = $value;
        $value = ":where" . count($this->where);

        $this->where[] = compact('column', 'operator', 'value', 'boolean');

        $this->binding($value, $binding);

        return $this;
    }

    /**
     * Handle where AND query
     * 
     * @param string $column
     * @param string $operator
     * @param string $value
     * @return $this
     */
    public function andWhere($column, $operator = null, $value = null)
    {
        return $this->where($column, $operator, $value, 'AND');
    }

    /**
     * Handle where OR query
     * 
     * @param string $column
     * @param string $operator
     * @param string $value
     * @return $this
     */
    public function orWhere($column, $operator = null, $value = null)
    {
        return $this->where($column, $operator, $value, 'OR');
    }

    /**
     * Handle Group by query
     * 
     * @param string $column
     * @param array $values
     * @return $this
     */
    public function groupby($groups)
    {
        $groups = is_array($groups) ? $groups : func_get_args();
        $this->groups = $groups;
        return $this;
    }

    /**
     * Handle Order by query
     * 
     * @param string $column
     * @param array $values
     * @return $this
     */
    public function orderby($orders)
    {
        $orders = is_array($orders) ? $orders : func_get_args();
        $this->orders = $orders;
        return $this;
    }

    /**
     * Handle Having query
     * 
     * @param string $column
     * @param string $operator
     * @param string $value
     * @param string $boolean
     * @return $this
     */
    public function having($column, $operator = null, $value = null, $boolean = 'and')
    {
        if (func_num_args() == 2) {
            list($value, $operator) = [$operator, '='];
        }

        if (is_null($value)) {
            $operator = '=';
        }

        $this->having[] = compact('column', 'operator', 'value', 'boolean');
        return $this;
    }

    /**
     * Handle Having OR query
     * 
     * @param string $column
     * @param string $operator
     * @param string $value
     * @return $this
     */
    public function orhaving($column, $operator = null, $value = null)
    {
        return $this->having($column, $operator, $value, 'or');
    }

    /**
     * Handle limit query
     * 
     * @param int $limit
     * @return $this
     */
    public function limit($limit)
    {
        $this->limit = $limit;
        return $this;
    }

    /**
     * Handle offset query
     * 
     * @param int $offset
     * @return $this
     */
    public function offset($offset)
    {
        $this->offset = $offset;
        return $this;
    }

    /**
     * Build query
     * 
     * @return string
     */
    public function build()
    {
        $query = $this->queryType;

        if ($query == 'select') {
            $query = $this->buildSelect();
        } elseif ($query == 'insert') {
            $query = $this->buildInsert();
        } elseif ($query == 'update') {
            $query = $this->buildUpdate();
        } elseif ($query == 'delete') {
            $query = $this->buildDelete();
        }

        return $query;
    }
}
