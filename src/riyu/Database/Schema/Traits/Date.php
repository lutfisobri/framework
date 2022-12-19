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

    public function dateInterval($name)
    {
        $this->addColumn('dateinterval', $name);
        return $this;
    }

    public function dateTimeTz($name)
    {
        $this->addColumn('datetimetz', $name);
        return $this;
    }

    public function timeTz($name)
    {
        $this->addColumn('timetz', $name);
        return $this;
    }

    public function timestampTz($name)
    {
        $this->addColumn('timestamptz', $name);
        return $this;
    }

    public function timeInterval($name)
    {
        $this->addColumn('timeinterval', $name);
        return $this;
    }

    public function timeIntervalTz($name)
    {
        $this->addColumn('timeintervaltz', $name);
        return $this;
    }

    public function timeTzInterval($name)
    {
        $this->addColumn('timetzinterval', $name);
        return $this;
    }

    public function timeTzIntervalTz($name)
    {
        $this->addColumn('timetzintervaltz', $name);
        return $this;
    }

    public function dateTimeInterval($name)
    {
        $this->addColumn('datetimeinterval', $name);
        return $this;
    }

    public function dateTimeIntervalTz($name)
    {
        $this->addColumn('datetimeintervaltz', $name);
        return $this;
    }

    public function dateTimeTzInterval($name)
    {
        $this->addColumn('datetimetzinterval', $name);
        return $this;
    }

    public function dateTimeTzIntervalTz($name)
    {
        $this->addColumn('datetimetzintervaltz', $name);
        return $this;
    }

    public function timestampInterval($name)
    {
        $this->addColumn('timestampinterval', $name);
        return $this;
    }

    public function timestampIntervalTz($name)
    {
        $this->addColumn('timestampintervaltz', $name);
        return $this;
    }

    public function timestampTzInterval($name)
    {
        $this->addColumn('timestamptzinterval', $name);
        return $this;
    }

    public function timestampTzIntervalTz($name)
    {
        $this->addColumn('timestamptzintervaltz', $name);
        return $this;
    }

    public function dateIntervalInterval($name)
    {
        $this->addColumn('dateintervalinterval', $name);
        return $this;
    }

    public function dateIntervalIntervalTz($name)
    {
        $this->addColumn('dateintervalintervaltz', $name);
        return $this;
    }

    public function dateIntervalTzInterval($name)
    {
        $this->addColumn('dateintervaltzinterval', $name);
        return $this;
    }

    public function dateIntervalTzIntervalTz($name)
    {
        $this->addColumn('dateintervaltzintervaltz', $name);
        return $this;
    }

    public function timeIntervalInterval($name)
    {
        $this->addColumn('timeintervalinterval', $name);
        return $this;
    }

    public function timeIntervalIntervalTz($name)
    {
        $this->addColumn('timeintervalintervaltz', $name);
        return $this;
    }

    public function timeIntervalTzInterval($name)
    {
        $this->addColumn('timeintervaltzinterval', $name);
        return $this;
    }

    public function timeIntervalTzIntervalTz($name)
    {
        $this->addColumn('timeintervaltzintervaltz', $name);
        return $this;
    }
}