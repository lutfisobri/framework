<?php

namespace Riyu\Database\Utils;

interface QueryInterface
{
    public function select($column = ['*']);
    public function insert($data = array());
    public function update($data = array());
    public function delete();
    public function get();
    public function first();
    public function where($column, $operator = '', $value = '');
    public function join($table, $column1, $column2, $operator = null,);
    public function leftJoin($table, $column1, $column2, $operator = null,);
    public function rightJoin($table, $column1, $column2, $operator = null,);
    public function innerJoin($table, $column1, $column2, $operator = null,);
    public function fullJoin($table, $column1, $column2, $operator = null,);
    public function outerJoin($table, $column1, $column2, $operator = null,);
    public function orderBy($column, $order);
    public function groupBy($column);
    public function all();
    public function save();
}
