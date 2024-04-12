<?php

namespace DGTournaments\Collections;


use Carbon\Carbon;
use DGTournaments\Models\Schedule;
use Illuminate\Database\Eloquent\Collection;

class ScheduleCollection extends Collection
{
    public function groupedByDay()
    {
        return $this->sortBy(function($item) {
            return $item->date->format('U');
        })->groupBy(function($item) {
            return $item->date->format('l') . ' (' . $item->date->format("jS") . ')';
        })->map(function(ScheduleCollection $items, $day) {

            return $items->filter(function($item) {
                return is_null($item->start) && is_null($item->end);
            })->merge($items->reject(function($item) {
                return is_null($item->start) && is_null($item->end);
            })->sortBy(function(Schedule $item) {
                if(is_null($item->start)) return 0;
                return Carbon::createFromFormat('Y-m-d H:i:s', $item->date->format('Y-m-d') . $item->start->format('H:i:s'))->format('U');
            }));

        });
    }
}