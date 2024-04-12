<?php

namespace DGTournaments\Repositories;


use DGTournaments\Models\Activity;
use Illuminate\Database\Eloquent\Collection;

class ActivityRepository
{
    protected $activity;

    protected $feedActivityTypes = [
        [
            'type' => 'pdga.rating.updated',
            'guest' => false
        ],
        [
            'type' => 'searches.tournaments.new',
            'guest' => false
        ],
        [
            'type' => 'tournament.course.created',
            'guest' => true
        ],
        [
            'type' => 'tournament.date.updated',
            'guest' => true
        ],
        [
            'type' => 'tournament.description.updated',
            'guest' => true
        ],
        [
            'type' => 'tournament.headquarters.updated',
            'guest' => true
        ],
        [
            'type' => 'tournament.links.updated',
            'guest' => true
        ],
        [
            'type' => 'tournament.media.updated',
            'guest' => true
        ],
        [
            'type' => 'tournament.name.updated',
            'guest' => true
        ],
        [
            'type' => 'tournament.player_pack.updated',
            'guest' => true
        ],
        [
            'type' => 'tournament.poster.updated',
            'guest' => true
        ],
        [
            'type' => 'tournament.registration.updated',
            'guest' => true
        ],
        [
            'type' => 'tournament.schedule.updated',
            'guest' => true
        ],
        [
            'type' => 'tournament.sponsorship.created',
            'guest' => true
        ],
        [
            'type' => 'tournament.user.assigned',
            'guest' => false
        ],
        [
            'type' => 'tournament_course.created',
            'guest' => false
        ],
    ];

    public function __construct(Activity $activity)
    {
        $this->activity = $activity;
        $this->feedActivityTypes = collect($this->feedActivityTypes);
    }

    public function getRecentActivityForGuests($limit = 40) : Collection
    {
        return $this->activity
            ->whereIn('type', $this->feedActivityTypes->where('guest', true)->pluck('type'))
            ->has('resource')
            ->with('resource')
            ->limit(40)
            ->latest()
            ->get();
    }
}