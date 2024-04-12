<?php namespace DGTournaments\Repositories;

use Carbon\Carbon;
use DGTournaments\Collections\TournamentsCollection;
use DGTournaments\Events\TournamentSubmitted;
use DGTournaments\Helpers\AlgoliaQuery;
use DGTournaments\Models\Classes;
use DGTournaments\Models\Course;
use DGTournaments\Models\Format;
use DGTournaments\Models\PdgaTier;
use DGTournaments\Models\Search;
use DGTournaments\Models\SpecialEventType;
use DGTournaments\Models\Tournament;
use DGTournaments\Services\DarkSky\DarkSkyApi;
use DGTournaments\Services\Foursquare\FoursquareApi;
use Illuminate\Support\Facades\Cache;

class TournamentRepository
{
    protected $tournament;

    protected $mapMarker;

    protected $foursquareApi;

    protected $darkskyApi;

    protected $course;

    public function __construct(Tournament $tournament, FoursquareApi $foursquareApi, DarkSkyApi $darkSkyApi, Course $course)
    {
        $this->tournament = $tournament;
        $this->foursquareApi = $foursquareApi;
        $this->darkSkyApi = $darkSkyApi;
        $this->course = $course;
    }

    public function createTournament($user, $attributes)
    {
        $attributes['slug'] = str_slug($attributes['name']);
        $attributes['start'] = Carbon::createFromFormat('n-j-Y', $attributes['start']);
        $attributes['end'] = Carbon::createFromFormat('n-j-Y', $attributes['end']);
        $tournament = $this->tournament->create($attributes);
        $tournament->authorization_email = $attributes['authorization_email'];

        if(isset($attributes['special_event_type_ids'])) $tournament->specialEventTypes()->sync($attributes['special_event_type_ids']);

        $tournament->classes()->sync($attributes['class_ids']);
        $tournament->managers()->attach($user->id);
        $tournament->save();

        event(new TournamentSubmitted($tournament, $user));

        return $tournament;
    }

    public function updateTournament(Tournament $tournament, $user, $attributes)
    {
        if(isset($attributes['name']) && $tournament->name != $attributes['name']) $attributes['slug'] = str_slug($attributes['name']);

        if(isset($attributes['start']))
        {
            if ($tournament->start->format('n-j-Y') != $attributes['start'])
                $attributes['start'] = Carbon::createFromFormat('n-j-Y', $attributes['start']);

            else unset($attributes['start']);
        }

        if(isset($attributes['end']))
        {
            if ($tournament->end->format('n-j-Y') != $attributes['end'])
                $attributes['end'] = Carbon::createFromFormat('n-j-Y', $attributes['end']);

            else unset($attributes['end']);
        }

        if(isset($attributes['special_event_type_ids'])) $tournament->specialEventTypes()->sync($attributes['special_event_type_ids']);
        else $tournament->specialEventTypes()->sync([]);

        if(isset($attributes['class_ids'])) $tournament->classes()->sync($attributes['class_ids']);
        else $tournament->classes()->sync([]);

        $tournament->update($attributes);

        return $tournament;
    }

    public function getWeather(Tournament $tournament)
    {
        $latLng = $tournament->getLatLng();
        $key = 'tournaments.' . $tournament->id . '.weather';

        if (Cache::has($key)) {
            return Cache::get($key);
        } else {

            $days = range(0, ($tournament->start->diffInDays($tournament->end)) + 2);
            $weather = [];
            foreach ($days as $day)
            {
                $weather[] = $this->darkSkyApi->getForecast($latLng[0], $latLng[1], $tournament->start->addDays($day));
            }

            foreach ($weather as $w)
            {
                if(empty($w)) return [];
            }

            Cache::put($key, $weather,  1440);
        }

        return $weather;
    }
//
//    public function getNearbyVenues(Tournament $tournament)
//    {
//        if(! $tournament->hasLocation()) return null;
//
//        $key = 'tournaments.' . $tournament->tournament_id . '.venues';
//        if(Cache::has($key)) {
//            return Cache::get($key);
//        } else {
//            $venues = $this->getVenues($tournament);
//            Cache::put($key, $venues, 720);
//        }
//
//        return $venues;
//    }
//
//    public function getVenues(Tournament $tournament)
//    {
//        if($tournament->courses->count())
//        {
//            $geo = [
//                $tournament->courses->avg('latitude'),
//                $tournament->courses->avg('longitude'),
//                5000
//            ];
//        }
//        else
//        {
//            $geo = [
//                $tournament->latitude,
//                $tournament->longitude,
//                5000
//            ];
//        }
//
//        $categories = collect([
//            'beer' => '4bf58dd8d48988d116941735',
//            'bar' => '50327c8591d4c4b30a586d5d',
//            'triangle' => '4bf58dd8d48988d1e4941735'
//        ]);
//
//        return $categories->flatMap(function($categoryId, $type) use ($geo) {
//
//            $response = $this->foursquareApi->getVenues($geo, $categoryId);
//
//            return collect($response['venues'])->map(function($venue) use ($type) {
//
//                $venue = $this->foursquareApi->getVenue($venue['id']);
//
//                return [
//                    'symbol' => $type,
//                    'venue' => collect($venue)->only(['id', 'name', 'contact', 'location', 'canonicalUrl'])
//                ];
//            });
//        });
//    }
//
//    public function getUnlisted()
//    {
//        return $this->tournament->orderBy('tournament_name', 'asc')->get()->reject(function(Tournament $tournament) {
//            return $tournament->indexingConditions();
//        });
//    }
//

    public function getSurroundingCourses(Tournament $tournament, $distance = 1)
    {
        return $this->course
            ->where('latitude', '>', $tournament->latitude - $distance)
            ->where('latitude', '<', $tournament->latitude + $distance)
            ->where('longitude', '>', $tournament->longitude - $distance)
            ->where('longitude', '<', $tournament->longitude + $distance)
            ->orderBy('name')
            ->get();
    }

    public function getPdgaListing($tournamentId)
    {
        return $this->tournament->where('data_source_id', 1)
            ->where('data_source_tournament_id', $tournamentId)
            ->first();
    }
}