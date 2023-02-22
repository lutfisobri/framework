<?php
namespace Riyu\Database\Schema\Traits;

trait Date
{
    public function date($name)
    {
        $this->addColumn('date', $name);
        return $this;
    }

    public function dateTime($name)
    {
        $this->addColumn('datetime', $name);
        return $this;
    }

    public function time($name)
    {
        $this->addColumn('time', $name);
        return $this;
    }

    public function timestamp($name, $nullable = false)
    {
        $this->addColumn('timestamp', $name, ['nullable' => $nullable]);
        return $this;
    }

    public function year($name)
    {
        $this->addColumn('year', $name);
        return $this;
    }
}