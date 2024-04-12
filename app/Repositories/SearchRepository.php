<?php

namespace DGTournaments\Repositories;


use Carbon\Carbon;
use DGTournaments\Collections\TournamentsCollection;
use DGTournaments\Helpers\AlgoliaQuery;
use DGTournaments\Models\Classes;
use DGTournaments\Models\Format;
use DGTournaments\Models\PdgaTier;
use DGTournaments\Models\Search;
use DGTournaments\Models\SpecialEventType;
use DGTournaments\Models\Tournament;
use DGTournaments\Models\User\User;
use Illuminate\Support\Collection;

class SearchRepository
{
    /**
     * @var Search
     */
    protected $search;

    protected $tournament;

    public function __construct(Search $search, Tournament $tournament)
    {
        $this->search = $search;
        $this->tournament = $tournament;
    }

    public function saveSearch($user, $attributes)
    {
        $query = parse_url($attributes['url'])['query'];

        $search = $this->search->make([
            'title' => $attributes['title'],
            'query' => $query,
            'wants_notification' => isset($attributes['wants_notification']),
            'searched_at' => isset($attributes['wants_notification']) ? Carbon::now() : null,
            'frequency' => isset($attributes['frequency']) ? $attributes['frequency'] : null
        ]);

        $search->user()->associate($user)->save();

        return $search;
    }

    public function getAllReadySearches() : Collection
    {
        return collect(['daily', 'weekly'])->flatMap(function($frequency) {
            return $this->getReadyByFrequency($frequency);
        });
    }

    public function getReadySearchesForActivities()
    {
        return $this->getAllReadySearches()->groupBy('user_id')->map(function(Collection $searches) {
            return [
                'user' => $searches->first()->user,
                'searches' => $searches
            ];
        });
    }

    public function getReadySearchesForNotifications()
    {
        return $this->getAllReadySearches()->filter(function(Search $search) {
            return $search->wants_notification == 1;
        })
        ->groupBy('user_id')->map(function(Collection $searches) {
            return [
                'user' => $searches->first()->user,
                'searches' => $searches
            ];
        });
    }

    public function getReadyByFrequency($frequency) : \Illuminate\Database\Eloquent\Collection
    {
        $interval = $frequency === 'daily' ? Carbon::now()->subHours(24) : Carbon::now()->subDays(7);

        return $this->search->whereFrequency($frequency)
            ->where('searched_at', '<', $interval)
            ->with('user')
            ->get();
    }

    public function getTournamentActivities() : Collection
    {
        return $this->getReadySearchesForActivities()->map(function($searches) {
            return [
                'user' => $searches['user'],
                'tournaments' => $searches['searches']->flatMap(function(Search $search) {
                    return $this->findTournamentBySearchQuery($search);
                })->unique('id')
            ];
        })->reject(function($notifications) {
            return $notifications['tournaments']->isEmpty();
        });
    }

    public function getTournamentNotifications() : Collection
    {
        return $this->getReadySearchesForNotifications()->map(function($searches) {
            return [
                'user' => $searches['user'],
                'tournaments' => $searches['searches']->flatMap(function(Search $search) {
                    return $this->findTournamentBySearchQuery($search);
                })->unique('id')
            ];
        })->reject(function($notifications) {
            return $notifications['tournaments']->isEmpty();
        });
    }

    public function findTournamentBySearchQuery(Search $search) : TournamentsCollection
    {
        $algoliaQuery = new AlgoliaQuery($search->query);

        $query = $this->tournament;

        if(! is_null($algoliaQuery->south()))
        {
            $query->where('latitude', '>', $algoliaQuery->south())
                ->where('latitude', '<', $algoliaQuery->north())
                ->where('longitude', '>', $algoliaQuery->west())
                ->where('longitude', '<', $algoliaQuery->east())
                ->where('created_at', '>', $search->frequency == 'daily' ? Carbon::now()->subDays(1) : Carbon::now()->subDays(7));
        }

        // Formats
        if(! is_null($algoliaQuery->formats()))
        {
            $formats = Format::whereIn('title', $algoliaQuery->formats())->select('id')->get();

            $query->whereIn('format_id', $formats->pluck('id'));
        }

        // Classes
        if(! is_null($algoliaQuery->classes()))
        {
            $classes = Classes::whereIn('title', $algoliaQuery->classes())->select('id')->get();

            $query->whereHas('classes', function ($query) use ($classes) {
                $query->whereIn('id', $classes->pluck('id'));
            });
        }

        // Dates
        if(! is_null($algoliaQuery->from())) $query->where('start', '>=', $algoliaQuery->from());
        if(! is_null($algoliaQuery->to())) $query->where('start', '<=', $algoliaQuery->to());

        // Pdga Tiers
        if(! is_null($algoliaQuery->pdgaTiers()))
        {
            $pdgaTiers = PdgaTier::whereIn('code', $algoliaQuery->pdgaTiers())->select('id')->get();

            $query->whereHas('pdgaTiers', function ($query) use ($pdgaTiers) {
                $query->whereIn('id', $pdgaTiers->pluck('id'));
            });
        }

        // Special Event Types
        if(! is_null($algoliaQuery->specialEventTypes()))
        {
            $specialEventTypes = SpecialEventType::whereIn('title', $algoliaQuery->specialEventTypes())->select('id')->get();

            $query->whereHas('specialEventTypes', function ($query) use ($specialEventTypes) {
                $query->whereIn('id', $specialEventTypes->pluck('id'));
            });
        }

        // Sanctioned
        if(! is_null($algoliaQuery->sanctioned()))
        {
            if(in_array('PDGA', $algoliaQuery->sanctioned())) $query->has('pdgaTiers');

            elseif(in_array('Unsanctioned', $algoliaQuery->sanctioned())) $query->doesntHave('pdgaTiers');
        }

        return $query->get();
    }

    public function updateSearches(User $user)
    {
        $user->searches->each(function(Search $search) {
            $search->update(['searched_at' => Carbon::now()]);
        });
    }
}