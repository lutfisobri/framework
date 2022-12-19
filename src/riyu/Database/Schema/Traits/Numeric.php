<?php
namespace Riyu\Database\Schema\Traits;

trait Numeric
{
    public function int($name, $length = 11)
    {
        $this->addColumn('int', $name, ['length' => $length]);
        return $this;
    }

    public function integer($name, $length = 11)
    {
        $this->addColumn('integer', $name, ['length' => $length]);
        return $this;
    }

    public function smallInt($name, $length = 6)
    {
        $this->addColumn('smallint', $name, ['length' => $length]);
        return $this;
    }

    public function tinyInt($name, $length = 4)
    {
        $this->addColumn('tinyint', $name, ['length' => $length]);
        return $this;
    }

    public function mediumInt($name, $length = 9)
    {
        $this->addColumn('mediumint', $name, ['length' => $length]);
        return $this;
    }

    public function bigInt($name, $length = 20)
    {
        $this->addColumn('bigint', $name, ['length' => $length]);
        return $this;
    }

    public function decimal($name, $length = 8, $decimals = 2)
    {
        $this->addColumn('decimal', $name, ['length' => $length, 'decimals' => $decimals]);
        return $this;
    }

    public function float($name, $length = 8, $decimals = 2)
    {
        $this->addColumn('float', $name, ['length' => $length, 'decimals' => $decimals]);
        return $this;
    }

    public function double($name, $length = 8, $decimals = 2)
    {
        $this->addColumn('double', $name, ['length' => $length, 'decimals' => $decimals]);
        return $this;
    }

    public function real($name, $length = 8, $decimals = 2)
    {
        $this->addColumn('real', $name, ['length' => $length, 'decimals' => $decimals]);
        return $this;
    }

    public function bit($name, $length = 1)
    {
        $this->addColumn('bit', $name, ['length' => $length]);
        return $this;
    }

    public function boolean($name)
    {
        $this->addColumn('boolean', $name);
        return $this;
    }

    public function serial($name, $length = 11)
    {
        $this->addColumn('serial', $name, ['length' => $length]);
        return $this;
    }

    public function bigSerial($name, $length = 20)
    {
        $this->addColumn('bigserial', $name, ['length' => $length]);
        return $this;
    }

    public function mediumSerial($name, $length = 9)
    {
        $this->addColumn('mediumserial', $name, ['length' => $length]);
        return $this;
    }

    public function smallSerial($name, $length = 6)
    {
        $this->addColumn('smallserial', $name, ['length' => $length]);
        return $this;
    }

    public function tinySerial($name, $length = 4)
    {
        $this->addColumn('tinyserial', $name, ['length' => $length]);
        return $this;
    }

    public function numeric($name, $length = 8, $decimals = 2)
    {
        $this->addColumn('numeric', $name, ['length' => $length, 'decimals' => $decimals]);
        return $this;
    }

    public function money($name, $length = 8, $decimals = 2)
    {
        $this->addColumn('money', $name, ['length' => $length, 'decimals' => $decimals]);
        return $this;
    }
}