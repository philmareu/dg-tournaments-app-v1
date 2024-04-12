<?php

namespace DGTournaments\Data;


use Carbon\Carbon;
use Illuminate\Contracts\Support\Arrayable;

class Dates implements Arrayable
{
    protected $start;

    protected $end;

    public function __construct(Carbon $start, Carbon $end)
    {
        $this->start = $start;
        $this->end = $end;
    }

    static public function make(Carbon $start, Carbon $end)
    {
        return new static($start, $end);
    }

    public function days()
    {
        return $this->start->diffInDays($this->end);
    }

    public function formattedDate()
    {
        return $this->start->format('M jS');
    }

    /**
     * @return bool
     */
    public function isOneDay() : bool
    {
        return $this->days() === 0;
    }

    public function formattedDateSpan($year = false)
    {
        if($this->isOneDay()) return $this->formattedDate();

        if($this->inSameMonth())
        {
            return $this->start->format('M jS') . ' - ' . $this->end->format('jS') . ($year ? 'Y' : '');
        }
        else
        {
            return $this->start->format('M jS') . ' - ' . $this->end->format('M jS') . ($year ? 'Y' : '');
        }
    }

    /**
     * @return bool
     */
    public function inSameMonth() : bool
    {
        return $this->start->format('F') == $this->end->format('F');
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return $this->formattedDateSpan();
    }
}