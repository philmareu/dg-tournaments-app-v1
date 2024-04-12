<?php

namespace DGTournaments\Http\Controllers;

use DGTournaments\Events\TournamentAutoAssigned;
use DGTournaments\Models\Activity;
use DGTournaments\Models\Follow;
use DGTournaments\Models\Tournament;
use DGTournaments\Models\User\User;
use DGTournaments\Repositories\ActivityRepository;
use DGTournaments\Repositories\BlogRepository;
use Illuminate\Support\Collection;

class HomeController extends Controller
{
    protected $activityRepository;

    protected $tournament;

    protected $user;

    protected $blogRepository;

    public function __construct(ActivityRepository $activityRepository, Tournament $tournament, User $user, BlogRepository $blogRepository)
    {
        $this->activityRepository = $activityRepository;
        $this->tournament = $tournament;
        $this->user = $user;
        $this->blogRepository = $blogRepository;
    }

    public function index()
    {
        if(auth()->guest()) return $this->guest();

        return $this->auth();
    }

    public function auth()
    {
        $feed = auth()->user()->feed->reject(function (Activity $activity) {
                return is_null($activity->resource);
            })
            ->map(function (Activity $activity) {
                return view('partials.activities.activity')->withActivity($activity)->render();
            });

//        $markers = $this->createMarkers(
//            auth()->user()->followingTournaments->load('resource')->pluck('resource')
//        );

        return view('pages.feed.auth')
            ->withUser(auth()->user())
            ->withUpcoming(auth()->user()->followingTournaments->reject(function(Follow $follow) {
                return is_null($follow->resource);
            })
                ->reject(function(Follow $follow) {
                return $follow->resource->isPast;
            })->sortBy(function(Follow $follow) {
                    return $follow->resource->start;
            }))
//            ->withMarkers($markers)
            ->withFeed($feed);
    }

    public function guest()
    {
        $activities = $this->activityRepository->getRecentActivityForGuests();

        $feed = $activities->map(function (Activity $activity) {
            return view('partials.activities.activity')->withActivity($activity)->render();
        });

        $markers = $this->createMarkers($this->getTournamentsFromActivities($activities));

        return view('pages.feed.guest')
            ->withActivities($activities)
            ->withRecentPosts($this->blogRepository->getRecent(10))
            ->withCounts([
                'tournaments' => $this->tournament->future()->count(),
                'users' => $this->user->count()
            ]);
//            ->withMarkers($markers);
//            ->withFeed($feed);
    }

    private function createMarkers(Collection $tournaments)
    {
        $tournaments = $this->normalizeTournamentCollection($tournaments);

        return $tournaments->map(function (Tournament $tournament) {

            return [
                'latitude' => $tournament->latitude,
                'longitude' => $tournament->longitude,
                'popup' => view('partials.markers.tournament')->withTournament($tournament)->render()
            ];
        });
    }

    private function getTournamentsFromActivities(Collection $activities)
    {
        return $activities->pluck('resource')->unique();
    }

    /**
     * I had to do this to normalize the tournament collection since different json results
     * where seen from ->toJson().
     *
     * @param Collection $tournaments
     * @return Collection
     */
    private function normalizeTournamentCollection(Collection $tournaments): Collection
    {
        return $this->tournament->whereIn('id', $tournaments->pluck('id'))->get(['latitude', 'longitude', 'name', 'id', 'slug']);
    }
}
