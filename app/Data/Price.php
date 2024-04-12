<?php

namespace DGTournaments\Data;


use Illuminate\Contracts\Support\Arrayable;

class Price implements Arrayable
{
    protected $cents;

    public function __construct($value, $type = 'cents')
    {
        $this->cents = $type == 'cents' ? (int) $value : (int) ($value * 100);
    }

    static public function make($value, $type = 'cents')
    {
        return new static($value, $type);
    }

    public function inCents()
    {
        return $this->cents;
    }

    public function inDollars()
    {
        return $this->cents / 100;
    }

    public function formatted()
    {
        return number_format($this->inDollars(), 2);
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return $this->inCents();
    }
}