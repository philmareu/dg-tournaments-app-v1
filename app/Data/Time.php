<?php

namespace DGTournaments\Data;


class Time
{
    protected $seconds = 0;

    const SECONDS_IN_MINUTE = 60;

    const MINUTES_IN_HOUR = 60;

    static public function make()
    {
        return new static;
    }

    public function seconds($value)
    {
        $this->seconds += $value;

        return $this;
    }

    public function minutes($value)
    {
        $this->seconds += ($value * 60);

        return $this;
    }

    public function hours($value)
    {
        $this->minutes($value * 60);

        return $this;
    }

    public function inSeconds()
    {
        return $this->seconds;
    }

    public function inMinutes()
    {
        return $this->seconds / 60;
    }
}