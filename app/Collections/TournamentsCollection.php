<?php

namespace DGTournaments\Collections;

use Illuminate\Database\Eloquent\Collection;

class TournamentsCollection extends Collection {

    public function withCourses()
    {
        $this->load('courses');

        return $this->filter(function($event) {
            return $event->courses->count();
        });
    }

    public function past()
    {
        return $this->filter(function($event) {
            return $event->isPast;
        });
    }

    public function notPast()
    {
        return $this->reject(function($event) {
            return $event->isPast;
        });
    }
}