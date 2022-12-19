<?php
namespace Riyu\Database\Schema;

use Closure;
use Riyu\Database\Schema\Blueprint;

class Schema
{
    public function create($table, Closure $callback)
    {
        return $this->build(tap($this->createBlueprint($table), function ($blueprint) use ($callback) {
            $blueprint->create();
            $callback($blueprint);
        }));
    }

    private function createBlueprint($table)
    {
        return new Blueprint($table);
    }

    private function build(Blueprint $blueprint)
    {
        $grammar = new Grammar();

        $sql = $grammar->compileCreate($blueprint);

        return $grammar->execute($sql, $blueprint->name);
    }

    public function drop($table)
    {
        $blueprint = new Blueprint($table);
        $grammar = new Grammar();
        $sql = $grammar->compileDrop($blueprint);
        return $grammar->delete($sql);
    }
}

if (! function_exists('tap')) {
    function tap($value, $callback = null)
    {
        $callback($value);

        return $value;
    }
}