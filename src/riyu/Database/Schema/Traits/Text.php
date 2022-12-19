<?php
namespace Riyu\Database\Schema\Traits;

trait Text
{
    public function string($name, $length = 255)
    {
        $this->addColumn('varchar', $name, ['length' => $length]);
        return $this;
    }

    public function text($name)
    {
        $this->addColumn('text', $name);
        return $this;
    }

    public function char($name, $length = 255)
    {
        $this->addColumn('char', $name, ['length' => $length]);
        return $this;
    }

    public function mediumText($name)
    {
        $this->addColumn('mediumtext', $name);
        return $this;
    }

    public function longText($name)
    {
        $this->addColumn('longtext', $name);
        return $this;
    }

    public function tinyText($name)
    {
        $this->addColumn('tinytext', $name);
        return $this;
    }

    public function binary($name, $length = 255)
    {
        $this->addColumn('binary', $name, ['length' => $length]);
        return $this;
    }

    public function blob($name)
    {
        $this->addColumn('blob', $name);
        return $this;
    }

    public function mediumBlob($name)
    {
        $this->addColumn('mediumblob', $name);
        return $this;
    }

    public function longBlob($name)
    {
        $this->addColumn('longblob', $name);
        return $this;
    }

    public function tinyBlob($name)
    {
        $this->addColumn('tinyblob', $name);
        return $this;
    }

    public function enum($name, $values)
    {
        $this->addColumn('enum', $name, ['values' => $values]);
        return $this;
    }

    public function set($name, $values)
    {
        $this->addColumn('set', $name, ['values' => $values]);
        return $this;
    }
}